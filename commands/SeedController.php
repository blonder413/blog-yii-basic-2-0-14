<?php
// commands/SeedController.php
namespace app\commands;

use yii\console\Controller;

use Yii;

use yii\db\Expression;
use yii\db\QueryBuilder;

class SeedController extends Controller
{
    public function actionIndex()
    {
        $faker = \Faker\Factory::create('es_ES');
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            for ($i=0; $i <10; $i++) {
                Yii::$app->db->createCommand()->batchInsert('users',
                    [
                        'name', 'username', 'auth_key', 'password_hash', 'password_reset_token',
                        'email', 'photo', 'status', 'created_at', 'updated_at'
                    ],
                    [
                        [
                            $faker->firstName,
                            $faker->userName,
                            $faker->word,
                            Yii::$app->security->generatePasswordHash('123456'),
                            null,
                            $faker->freeEmail,
                            $faker->slug . '.jpg',
                            10,
                            new Expression('NOW()'),
                            new Expression('NOW()')
                        ],
                    ]
                )->execute();
                
            }
            
            print('users inserted...');
    
            for ($i=0; $i <10; $i++){

                Yii::$app->db->createCommand()->batchInsert('categories',
                    [
                        'category', 'slug', 'image', 'description',
                        'created_by', 'created_at', 'updated_by', 'updated_at'
                    ],
                    [
                        [
                            $faker->unique()->word,
                            $faker->unique()->slug,
                            'image-' . $i . '.png',
                            $faker->text(200),
                            $faker->numberBetween(1,10),
                            new Expression('NOW()'),
                            $faker->numberBetween(1,10),
                            new Expression('NOW()')
                        ],
                    ]
                )->execute();
            }
            
            print('categories inserted...');
            
            for ($i=0; $i <3; $i++) {

                Yii::$app->db->createCommand()->batchInsert('types',
                    [
                        'type',
                        'created_by', 'created_at', 'updated_by', 'updated_at'
                    ],
                    [
                        [
                            $faker->randomElement(['article','code','video']),
                            $faker->numberBetween(1,10),
                            new Expression('NOW()'),
                            $faker->numberBetween(1,10),
                            new Expression('NOW()')
                        ],
                    ]
                )->execute();
            }
            
            print('types inserted...');
            
            $video = '<iframe width="560" height="315" src="https://www.youtube.com/embed/h371D0W46Dk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
            for ($i=0; $i <100; $i++) {
                
                Yii::$app->db->createCommand()->batchInsert('articles',
                    [
                        'number', 'title', 'slug', 'topic', 'detail', 'abstract', 'video', 'type_id',
                        'download', 'category_id', 'tags', 'status', 'visit_counter', 'download_counter',
                        'course_id', 'created_by', 'created_at', 'updated_by', 'updated_at'
                    ],
                    [
                        [
                            $faker->numberBetween(1,10),
                            $faker->unique()->text(100),
                            $faker->unique()->slug,
                            $faker->text(100),
                            $faker->text(300),
                            $faker->text(200),
                            $video,
                            $faker->numberBetween(1,3),
                            $faker->text(100),
                            $faker->numberBetween(1,10),
                            $faker->text(20),
                            random_int(0,1),
                            random_int(0,100),
                            random_int(0,100),
                            null,
                            $faker->numberBetween(1,10),
                            new Expression('NOW()'),
                            $faker->numberBetween(1,10),
                            new Expression('NOW()')
                        ],
                    ]
                )->execute();
            }
            
            print('articles inserted...');
            
            for ($i=0; $i <100; $i++){
                
                Yii::$app->db->createCommand()->batchInsert('comments',
                    [
                        'name', 'email', 'web', 'rel', 'comment', 'date',
                        'article_id', 'status', 'client_ip', 'client_port'
                    ],
                    [
                        [
                            $faker->firstName,
                            $faker->freeEmail,
                            $faker->url,
                            'no follow',
                            $faker->text(200),
                            new Expression('NOW()'),
                            $faker->numberBetween(1,100),
                            $faker->numberBetween(0,1),
                            $faker->word,
                            '413'
                        ]
                    ]
                )->execute();
            }
            
            print('comments inserted...');
            
            $transaction->commit();
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}