<?php declare(strict_types=1);

namespace LeoCavalcante\Ippo;

use Symfony\Component\Yaml\Yaml;

class Ippo
{
    private $config;
    private $twig;

    static public function fromYaml(string $filename)
    {
        return new Ippo(Yaml::parse(file_get_contents($filename)));
    }

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__.'/res'));

        $this->twig->addFilter(new \Twig_Filter('ucfirst', 'ucfirst'));

        $this->twig->addFilter(new \Twig_Filter('snake_case', function (string $input): string {
            return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $input));
        }));
    }

    public function generate(): array
    {
        $namespace = $this->config['namespace'];

        return array_map(function (array $definition) use ($namespace) {
            $keys = array_keys($definition);
            $vals = array_values($definition);

            $className = $keys[0];
            $extendsClassName = $vals[0];

            $attributes = array_combine(array_slice($keys, 1), array_slice($vals, 1));
            $attributes = array_map(function ($attr): array {
                if (is_array($attr)) {
                    return ['type' => $attr[0], 'default' => $attr[1]];
                }

                return ['type' => $attr];
            }, $attributes);

            $contents = $this->twig->render('template.twig', compact('className', 'extendsClassName', 'namespace', 'attributes'));

            return [$className, $contents];
        }, $this->config['definitions']);
    }
}
