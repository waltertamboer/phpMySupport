<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\I18n;

use Support\System\Domain\I18n\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SymfonyTranslator implements Translator
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function translate(?string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    public function setLocale(string $locale): void
    {
        $this->translator->setLocale($locale);
    }
}
