<?php

declare(strict_types=1);

namespace App\UI\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostOperation;
use App\Api\DTO\CreatePost;
use App\Api\StateProcessor\CreatePostProcessor;
use App\Api\StateProvider\GetPostsProvider;

#[ApiResource(
    operations: [
        new GetCollection(paginationEnabled: true, provider: GetPostsProvider::class),
        new PostOperation(input: CreatePost::class, processor: CreatePostProcessor::class),
    ]
)]
final class Post
{
}
