<?php

declare(strict_types=1);

namespace Subcore\SwDevEnvHelper\Twig;

use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Storefront\Framework\Twig\Extension\UrlEncodingTwigFilter as BaseUrlEncodingTwigFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Twig\TwigFilter;

class UrlEncodingTwigFilter extends BaseUrlEncodingTwigFilter
{
    public function __construct(
        private readonly SystemConfigService $systemConfigService,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('sw_encode_url', $this->encodeUrlExtension(...)),
            new TwigFilter('sw_encode_media_url', $this->encodeMediaUrlExtension(...)),
        ];
    }

    public function encodeUrlExtension(?string $mediaUrl): ?string
    {
        $encodedPath = $this->encodeUrl($mediaUrl);
        if ($encodedPath === null) {
            return '';
        }

        return str_replace(
            $this->systemConfigService->get('SubcoreSwDevEnvHelper.config.imageDomainSearch'),
            $this->systemConfigService->get('SubcoreSwDevEnvHelper.config.imageDomainReplace'),
            $encodedPath
        );
    }

    public function encodeMediaUrlExtension(?MediaEntity $media): ?string
    {
        if ($media === null || !$media->hasFile()) {
            return null;
        }

        return $this->encodeUrlExtension($media->getUrl());
    }
}
