<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class BooleanToDateTransformer implements DataTransformerInterface
{
    public function transform($termsAcceptedAt)
    {
        if ($termsAcceptedAt) {
            return true;
        }
    }

    public function reverseTransform($termsAcceptedAt)
    {
        if ($termsAcceptedAt) {
            return new \DateTime();
        }
    }
}
