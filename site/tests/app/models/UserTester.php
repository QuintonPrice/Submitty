<?php

namespace tests\app\models;

use app\libraries\Core;
use app\models\User;

class UserTester extends \PHPUnit\Framework\TestCase {
    private $core;
    public function setUp(): void {
        $this->core = $this->createMock(Core::class);
    }

    public function testUserNoPreferred() {
        $details = array(
            'user_id' => "test",
            'anon_id' => "TestAnon",
            'user_numeric_id' => '123456789',
            'user_password' => "test",
            'user_firstname' => "User",
            'user_preferred_firstname' => null,
            'user_lastname' => "Tester",
            'user_preferred_lastname' => null,
            'user_email' => "test@example.com",
            'user_group' => User::GROUP_INSTRUCTOR,
            'registration_section' => 1,
            'rotating_section' => null,
            'manual_registration' => false,
            'grading_registration_sections' => array(1, 2)
        );
        $user = new User($this->core, $details);
        $this->assertEquals($details['user_id'], $user->getId());
        $this->assertEquals($details['anon_id'], $user->getAnonId());
        $this->assertEquals($details['user_numeric_id'], $user->getNumericId());
        $this->assertEquals($details['user_firstname'], $user->getLegalFirstName());
        $this->assertEquals($details['user_preferred_firstname'], $user->getPreferredFirstName());
        $this->assertEquals($details['user_firstname'], $user->getDisplayedFirstName());
        $this->assertEquals($details['user_preferred_lastname'], $user->getPreferredLastName());
        $this->assertEquals($details['user_lastname'], $user->getLegalLastName());
        $this->assertEquals($details['user_email'], $user->getEmail());
        $this->assertEquals($details['user_group'], $user->getGroup());
        $this->assertEquals($details['registration_section'], $user->getRegistrationSection());
        $this->assertEquals($details['rotating_section'], $user->getRotatingSection());
        $this->assertEquals($details['manual_registration'], $user->isManualRegistration());
        $this->assertEquals(array(1,2), $user->getGradingRegistrationSections());
        $this->assertTrue($user->accessAdmin());
        $this->assertTrue($user->accessFullGrading());
        $this->assertTrue($user->accessGrading());
        $this->assertTrue($user->isLoaded());
    }

    public function testUserPreferred() {
        $details = array(
            'user_id' => "test",
            'anon_id' => "TestAnon",
            'user_numeric_id' => '123456789',
            'user_firstname' => "User",
            'user_preferred_firstname' => "Paul",
            'user_lastname' => "Tester",
            'user_preferred_lastname' => "Bunyan",
            'user_email' => "test@example.com",
            'user_group' => User::GROUP_INSTRUCTOR,
            'registration_section' => 1,
            'rotating_section' => null,
            'manual_registration' => false,
            'grading_registration_sections' => array(1,2)
        );
        $user = new User($this->core, $details);
        $this->assertEquals($details['user_id'], $user->getId());
        $this->assertEquals($details['anon_id'], $user->getAnonId());
        $this->assertEquals($details['user_numeric_id'], $user->getNumericId());
        $this->assertEquals($details['user_firstname'], $user->getLegalFirstName());
        $this->assertEquals($details['user_preferred_firstname'], $user->getPreferredFirstName());
        $this->assertEquals($details['user_preferred_firstname'], $user->getDisplayedFirstName());
        $this->assertEquals($details['user_lastname'], $user->getLegalLastName());
        $this->assertEquals($details['user_preferred_lastname'], $user->getPreferredLastName());
        $this->assertEquals($details['user_preferred_lastname'], $user->getDisplayedLastName());
    }

    public function testPassword() {
        $details = array(
            'user_id' => "test",
            'user_numeric_id' => "123456789",
            'user_password' => "test",
            'user_firstname' => "User",
            'user_preferred_firstname' => null,
            'user_lastname' => "Tester",
            'user_preferred_lastname' => null,
            'user_email' => "test@example.com",
            'user_group' => User::GROUP_INSTRUCTOR,
            'registration_section' => 1,
            'rotating_section' => null,
            'manual_registration' => false,
            'grading_registration_sections' => array(1,2)
        );
        $user = new User($this->core, $details);
        $this->assertTrue(password_verify("test", $user->getPassword()));
        $user->setPassword("test1");
        $this->assertTrue(password_verify("test1", $user->getPassword()));
        $user->setPassword(password_hash("test2", PASSWORD_DEFAULT));
        $this->assertTrue(password_verify("test2", $user->getPassword()));
    }

    public function testToObject() {
        $details = array(
            'user_id' => "test",
            'anon_id' => "TestAnonymous",
            'user_numeric_id' => '123456789',
            'user_password' => "test",
            'user_firstname' => "User",
            'user_preferred_firstname' => null,
            'user_lastname' => "Tester",
            'user_preferred_lastname' => null,
            'user_email' => "test@example.com",
            'user_group' => User::GROUP_INSTRUCTOR,
            'user_access_level' => User::LEVEL_FACULTY,
            'registration_section' => 1,
            'rotating_section' => null,
            'manual_registration' => false,
            'grading_registration_sections' => array(1,2)
        );
        $user = new User($this->core, $details);
        $actual = $user->toArray();
        password_verify("test", $actual['password']);
        unset($actual['password']);
        ksort($actual);
        $expected = array(
            'displayed_first_name' => 'User',
            'displayed_last_name' => 'Tester',
            'email' => 'test@example.com',
            'legal_first_name' => 'User',
            'grading_registration_sections' => array(1,2),
            'group' => User::GROUP_INSTRUCTOR,
            'access_level' => User::LEVEL_FACULTY,
            'id' => 'test',
            'legal_last_name' => 'Tester',
            'loaded' => true,
            'manual_registration' => false,
            'preferred_first_name' => "",
            'preferred_last_name' => "",
            'numeric_id' => '123456789',
            'registration_section' => 1,
            'rotating_section' => null,
            'modified' => true,
            'anon_id' => "TestAnonymous",
            'user_updated' => false,
            'instructor_updated' => false,
            'notification_settings' => array(
                'reply_in_post_thread' => false,
                'merge_threads' => false,
                'all_new_threads' => false,
                'all_new_posts' => false,
                'all_modifications_forum' => false,
                'team_invite' => true,
                'team_joined' => true,
                'team_member_submission' => true,
                'self_notification' => false,
                'reply_in_post_thread_email' => false,
                'merge_threads_email' => false,
                'all_new_threads_email' => false,
                'all_new_posts_email' => false,
                'all_modifications_forum_email' => false,
                'team_invite_email' => true,
                'team_joined_email' => true,
                'team_member_submission_email' => true,
                'self_notification_email' => false
            )
        );
        $this->assertEquals($expected, $actual);
    }

    public function testErrorUser() {
        $user = new User($this->core, array());
        $this->assertFalse($user->isLoaded());
        $this->assertNull($user->getId());
    }

    public function testGetTimeZoneNiceFormatExplicitlySet() {
        $user = new User($this->core, [
            'user_id' => 'test',
            'user_firstname' => 'test',
            'user_lastname' => 'test',
            'user_email' => 'user@email.com',
            'time_zone' => 'NOT_SET/NOT_SET'
        ]);
        $this->assertEquals('NOT SET', $user->getTimeZoneNiceFormat());
    }

    public function testGetUTCOffsetExplicitlySet() {
        $user = new User($this->core, [
            'user_id' => 'test',
            'user_firstname' => 'test',
            'user_lastname' => 'test',
            'user_email' => 'user@email.com',
            'time_zone' => 'NOT_SET/NOT_SET'
        ]);
        $this->assertEquals('NOT SET', $user->getUTCOffset());
    }
}
