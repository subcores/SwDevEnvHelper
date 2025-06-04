<?php

declare(strict_types=1);

namespace Subcore\SwDevEnvHelper\Twig;

use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContext;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;
use Shopware\Storefront\Framework\Twig\Extension\UrlEncodingTwigFilter as BaseUrlEncodingTwigFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\TwigFilter;

class UrlEncodingTwigFilter extends BaseUrlEncodingTwigFilter
{
    public function __construct(
        private readonly SystemConfigService $systemConfigService,
        private readonly RequestStack $requestStack,
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

        $salesChannelId = $this->requestStack->getCurrentRequest()?->get('sw-sales-channel-context')?->getSalesChannelId();

        return str_replace(
            $this->systemConfigService->get('SubcoreSwDevEnvHelper.config.imageDomainSearch', $salesChannelId),
            $this->systemConfigService->get('SubcoreSwDevEnvHelper.config.imageDomainReplace', $salesChannelId),
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
