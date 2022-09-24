<?php

namespace App\Serializer;

use App\Entity\User;
use App\Service\FileUploader;
use App\Entity\CabinetMedical;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

final class CabinetNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private FileUploader $fileUploader;
    private const ALREADY_CALLED = 'CABINETMEDICALS_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(FileUploader $fileUploader) {
        $this->fileUploader = $fileUploader;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof CabinetMedical;
    }

    public function normalize($object, ?string $format = null, array $context = []) {
        $context[self::ALREADY_CALLED] = true;

        $object->setLogo($this->fileUploader->getUrl($object->getLogo()));

        return $this->normalizer->normalize($object, $format, $context);
    }
}
