<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Tag;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionTagFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        TagFactory::createMany(100);
        $users = UserFactory::createMany(20);

        $questions = QuestionFactory::createMany(20 , function () use ($users) {
            return [
                'questionTags' => QuestionTagFactory::new(function () {
                    return [
                        'tag' => TagFactory::random() ,
                    ];

                })->many(1 , 5) ,
                'user' => $users[array_rand($users)] ,
            ];
        });
        QuestionFactory::new(function () use ($users) {
            return [
                'user' => $users[array_rand($users)] ,
            ];
        }
        )->unpublished()->many(5)->create();

        AnswerFactory::createMany(100 , function () use ($questions , $users) {
            return [
                'question' => $questions[array_rand($questions)] ,
                'user' => $users[array_rand($users)] ,
            ];
        });
        AnswerFactory::new(function () use ($users) {
            return [
                'user' => $users[array_rand($users)] ,
            ];
        }
        )->needsApproval()->many(20)->create();

        $manager->flush();
    }
}
