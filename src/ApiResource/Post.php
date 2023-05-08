<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Api\StateProvider\GetPostsProvider;

#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: true, provider: GetPostsProvider::class),
    ]
)]
final class Post
{
}
