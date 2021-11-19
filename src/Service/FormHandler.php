<?php

declare(strict_types=1);

namespace App\Service;

use ReflectionClass;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class FormHandler
{
    private FormFactoryInterface $formFactory;
    private RequestStack $requestStack;

    public function __construct(
        FormFactoryInterface $formFactory, 
        RequestStack $requestStack
        )
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
    }

    public function handle(
        object $object,
        ?callable $callable = null,
        string $flashMessage = ''
    ) : FormInterface
    {
        $reflectionClass = new ReflectionClass($object);
        $formType = str_replace('Entity','Form',$reflectionClass->getName().'Type'::class);
        $form = $this->formFactory->create($formType,$object)->handleRequest($this->requestStack->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->requestStack->getSession()->getFlashBag()->add('notice', $flashMessage);
            if (is_callable($callable)) {
                $callable($object);
            }
        }
        return $form;
    }
}
