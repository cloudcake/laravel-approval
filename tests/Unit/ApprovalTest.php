<?php

namespace Approval\Tests\Unit;

use Approval\Models\Modification;
use Approval\Tests\Models\Post;
use Approval\Tests\Models\User;
use Approval\Tests\TestCase;

class ApprovalTest extends TestCase
{
    public function testApprovalProcessCreatedOnUpdate()
    {
        auth()->login(User::first());

        $post = Post::first();

        $originalTitle = $post->title;
        $originalContent = $post->content;

        $newTitle = 'Trigger Approval';
        $newContent = 'Something Bold';

        $post->title = $newTitle;
        $post->content = $newContent;
        $post->save();
        $post->refresh();

        $modification = $post->modifications()->first();

        $this->assertTrue($post->title != $newTitle);
        $this->assertTrue($post->modifications()->count() === 1);
        $this->assertTrue($modification->modifications['title']['original'] == $originalTitle);
        $this->assertTrue($modification->modifications['content']['original'] == $originalContent);
        $this->assertTrue($modification->modifications['title']['modified'] == $newTitle);
        $this->assertTrue($modification->modifications['content']['modified'] == $newContent);
    }

    public function testApprovalProcessCreatedOnCreate()
    {
        auth()->login(User::first());

        $post = Post::create([
          'title'   => 'Trigger Approval',
          'content' => 'Sweet Carrot',
        ]);

        $post->refresh();

        $modification = Modification::creations()->first();

        $this->assertTrue($modification->modifications['title']['original'] == null);
        $this->assertTrue($modification->modifications['content']['original'] == null);
        $this->assertTrue($modification->modifications['title']['modified'] == $post->title);
        $this->assertTrue($modification->modifications['content']['modified'] == $post->content);
        $this->assertTrue($modification->modifiable_id == null);
    }
}
