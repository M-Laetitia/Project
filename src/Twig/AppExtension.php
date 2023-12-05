<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('formatDate', [$this, 'formatDate']),
        ];
    }

    public function formatDate(\DateTimeInterface $dateTime, string $format = 'd-m-Y'): string
    {
        return $dateTime->format($format);
    }
}