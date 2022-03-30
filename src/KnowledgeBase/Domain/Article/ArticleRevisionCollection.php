<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @extends ArrayCollection<int, ArticleRevision>
 */
final class ArticleRevisionCollection extends ArrayCollection
{
}
