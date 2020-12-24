<?php

namespace Approval\Tests\Unit;

use Approval\Tests\TestCase;
use Approval\Tests\Models\User;
use Approval\Tests\Models\Post;
use Approval\Models\Modification;

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


    public function testDisapprovalProcessCreatedOnUpdate()
    {
        auth()->login(User::first());

        $post = Post::first();

        $oldTitle =  $post->title;
        $oldContent = $post->content;

        $newTitle = 'Trigger Approval';
        $newContent = 'Something Bold';

        $post->title = $newTitle;
        $post->content = $newContent;
        $post->save();
        $post->refresh();

        $modification = $post->modifications()->first();

        User::first()->disapprove($modification, "I dont like the new title.");

        $this->assertNotEmpty($post->modifications);

        $this->assertEquals($oldTitle, $post->title);
        $this->assertEquals($oldContent, $post->content);
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

    public function testItCanAddReasonToApprove()
    {
        auth()->login(User::first());

        Post::create([
            'title'   => 'Trigger Approval',
            'content' => 'Sweet Carrot',
        ]);

        $modification = Modification::creations()->first();


        User::first()->approve($modification, "You have create a great post");


        $this->assertEquals("You have create a great post", $modification->approvals()->first()->reason);
    }


    public function testItCanAddReasonToDisapprove()
    {
        auth()->login(User::first());

        Post::create([
            'title'   => 'Trigger Approval',
            'content' => 'Sweet Carrot',
        ]);

        $modification = Modification::creations()->first();


        User::first()->disapprove($modification, "Your post is not complete!");

        $this->assertEquals("Your post is not complete!", $modification->disapprovals()->first()->reason);
    }


    public function testItCanAddReasonToDisapproval()
    {
        auth()->login(User::first());

        Post::create([
            'title'   => 'Trigger Approval',
            'content' => 'Sweet Carrot',
        ]);

        $modification = Modification::creations()->first();


        User::first()->disapprove($modification);

        $this->assertNull($modification->disapprovals()->first()->reason);
    }
}
