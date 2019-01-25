<?php

namespace Approval\Tests\Unit;

use Approval\Tests\Models\Post;
use Approval\Tests\Models\User;
use Approval\Tests\TestCase;

class ApprovalTest extends TestCase
{
    public function testApprovalProcessCreated()
    {
        auth()->login(User::first());

        $post = Post::first();

        $originalTitle = $post->title;
        $originalContent = $post->content;

        $post->title = 'Something New';
        $post->content = 'Something Bold';
        $post->save();
        $post->refresh();

        $this->assertTrue($post->title != 'Something New');
        $this->assertTrue($post->modifications()->count() === 1);
        $this->assertTrue($post->modifications()->first()->modifications['title']['original'] == $originalTitle);
        $this->assertTrue($post->modifications()->first()->modifications['content']['original'] == $originalContent);
        $this->assertTrue($post->modifications()->first()->modifications['title']['modified'] == 'Something New');
        $this->assertTrue($post->modifications()->first()->modifications['content']['modified'] == 'Something Bold');
    }
}
