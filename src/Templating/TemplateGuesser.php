<?php

namespace App\Templating;

use Doctrine\Common\Util\ClassUtils;
use Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser as BaseTemplateGuesser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Overriding Framework extra bundle Template Guesser to fit ADR pattern
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class TemplateGuesser extends BaseTemplateGuesser
{
    private $kernel;
    private $controllerPatterns;

    /**
     * {@inheritdoc}
     */
    public function __construct(KernelInterface $kernel, $controllerPatterns = [])
    {
        $this->kernel = $kernel;
        $controllerPatterns[] = '/Action\\\(.+)$/';
        $this->controllerPatterns = $controllerPatterns;

        parent::__construct($kernel, $controllerPatterns);
    }

    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     */
    public function guessTemplateName($controller, Request $request)
    {
        if (\is_object($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        } elseif (!\is_array($controller)) {
            throw new \InvalidArgumentException(sprintf('First argument of %s must be an array callable or an object defining the magic method __invoke. "%s" given.', __METHOD__, \gettype($controller)));
        }

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : \get_class($controller[0]);

        $matchController = null;
        foreach ($this->controllerPatterns as $pattern) {
            if (preg_match($pattern, $className, $tempMatch)) {
                $matchController = str_replace('\\', '/', strtolower(preg_replace('/([a-z\d])([A-Z])/', '\\1_\\2', $tempMatch[1])));
                break;
            }
        }
        if (null === $matchController) {
            throw new \InvalidArgumentException(sprintf('The "%s" class does not look like a controller class (its FQN must match one of the following regexps: "%s")', \get_class($controller[0]), implode('", "', $this->controllerPatterns)));
        }

        if ('__invoke' === $controller[1]) {
            $matchAction = $matchController;
            $matchController = null;
        } else {
            $matchAction = preg_replace('/Action$/', '', $controller[1]);
        }

        $matchAction = strtolower(preg_replace('/([a-z\d])([A-Z])/', '\\1_\\2', $matchAction));
        $bundleName = $this->getBundleForClass($className);

        return sprintf(($bundleName ? '@'.$bundleName.'/' : '').$matchController.($matchController ? '/' : '').$matchAction.'.'.$request->getRequestFormat().'.twig');
    }

    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     */
    private function getBundleForClass($class)
    {
        $reflectionClass = new \ReflectionClass($class);
        $bundles = $this->kernel->getBundles();

        do {
            $namespace = $reflectionClass->getNamespaceName();
            foreach ($bundles as $bundle) {
                if ('Symfony\Bundle\FrameworkBundle' === $bundle->getNamespace()) {
                    continue;
                }
                if (0 === strpos($namespace, $bundle->getNamespace())) {
                    return preg_replace('/Bundle$/', '', $bundle->getName());
                }
            }
            $reflectionClass = $reflectionClass->getParentClass();
        } while ($reflectionClass);
    }
}