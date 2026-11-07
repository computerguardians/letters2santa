<?php

namespace Tests\Unit;

use App\Services\TextModerationService;
use Tests\TestCase;

class TextModerationTest extends TestCase
{
    private TextModerationService $svc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->svc = new TextModerationService;
    }

    public function test_clean_letter_has_no_flags(): void
    {
        $this->assertSame([], $this->svc->check('Dear Santa, I would like a red bike and a book about dinosaurs. I have been good. Love Emma', 'Emma'));
    }

    public function test_detects_phone_numbers(): void
    {
        $this->assertContains('phone', $this->svc->check('Call my mum on 0412 345 678'));
        $this->assertContains('phone', $this->svc->check('my number is +61 3 9123 4567'));
        $this->assertNotContains('phone', $this->svc->check('I am 7 and I want 3 presents and 100 lollies'));
    }

    public function test_detects_emails_and_urls(): void
    {
        $this->assertContains('email', $this->svc->check('write back to mum@example.com'));
        $this->assertContains('url', $this->svc->check('see www.example.com/me'));
    }

    public function test_detects_street_addresses(): void
    {
        $this->assertContains('address', $this->svc->check('I live at 12 Smith Street, come visit'));
        $this->assertContains('address', $this->svc->check('our house is 4a Banksia Cres'));
        $this->assertNotContains('address', $this->svc->check('I want 2 trucks'));
    }

    public function test_detects_profanity_including_leetspeak(): void
    {
        $this->assertContains('profanity', $this->svc->check('this is sh1t'));
        $this->assertContains('profanity', $this->svc->check('Santa you are a bastard'));
        $this->assertNotContains('profanity', $this->svc->check('I love my scunthorpe shirt and a basset hound'));
    }

    public function test_flags_possible_surname_in_child_name(): void
    {
        $this->assertContains('possible_surname', $this->svc->check(null, 'Emma Smith'));
        $this->assertNotContains('possible_surname', $this->svc->check(null, 'Emma-Rose'));
    }

    public function test_flags_school_mentions_as_pii_hint(): void
    {
        $this->assertContains('pii_hint', $this->svc->check('I go to Tarneit Primary School'));
    }
}
