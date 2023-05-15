<?php

declare(strict_types=1);

/*
 * This file is part of the package t3g/blog.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace T3G\AgencyPack\Blog\Tests\Functional\ViewHelpers;

use T3G\AgencyPack\Blog\Tests\Functional\SiteBasedTestCase;
use TYPO3\CMS\Core\Database\ConnectionPool;

final class AuthorViewHelperTest extends SiteBasedTestCase
{
    /**
     * @test
     * @dataProvider renderDataProvider
     */
    public function render(string $template, string $expected): void
    {
        $this->createTestSite();

        (new ConnectionPool())->getConnectionForTable('pages')->insert(
            'tx_blog_domain_model_author',
            [
                'uid' => 100,
                'pid' => self::STORAGE_PID,
                'name' => 'TYPO3 Inc Team',
                'slug' => 'typo3-inc-team'
            ]
        );

        $instructions = [
            [
                'type' => 'author',
                'uid' => 100,
                'as' => 'author',
            ]
        ];

        self::assertSame(
            $expected,
            $this->renderFluidTemplateInTestSite($template, $instructions)
        );
    }

    public static function renderDataProvider(): array
    {
        return [
            'simple' => [
                '<blogvh:link.author author="{test.author}" />',
                '<a href="/author/author/typo3-inc-team">TYPO3 Inc Team</a>',
            ],
            'target' => [
                '<blogvh:link.author author="{test.author}" target="_blank" />',
                '<a target="_blank" href="/author/author/typo3-inc-team">TYPO3 Inc Team</a>',
            ],
            'rel' => [
                '<blogvh:link.author author="{test.author}" rel="noreferrer" />',
                '<a rel="noreferrer" href="/author/author/typo3-inc-team">TYPO3 Inc Team</a>',
            ],
            'rss' => [
                '<blogvh:link.author author="{test.author}" rss="true" />',
                '<a href="/author/author/typo3-inc-team/blog.author.xml">TYPO3 Inc Team</a>',
            ],
            'content' => [
                '<blogvh:link.author author="{test.author}">Hello</blogvh:link.author>',
                '<a href="/author/author/typo3-inc-team">Hello</a>',
            ],
            'class' => [
                '<blogvh:link.author author="{test.author}" class="class" />',
                '<a class="class" href="/author/author/typo3-inc-team">TYPO3 Inc Team</a>',
            ],
        ];
    }
}
