<?php

namespace Tests\Functional\Controller;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Nelmio\Alice\Loader\NativeLoader;

class QuizControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    private $client;
    
    /**
     * @var EntityManager $em
     */
    private $em;
    
    public function setUp()
    {
        $this->client = self::createClient();
        $kernel = self::bootKernel();
        
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $loader = new NativeLoader();
        $loader->loadFile(__DIR__ . '/../../DataFixture/QuizFixtures.yml');
    }
    
    public function testQuizList()
    {
        $crawler = $this->client->request('GET', '/quiz/');
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        $crawler = $crawler->filter("table>tbody>tr");
        
        $nodeValues = $crawler->each(
            function (Crawler $node) {
                return $node->children()->first()->text();
            }
        );
        
        $this->assertCount(4, $nodeValues);
        $this->assertEquals('11', $nodeValues[2]);
    }
    
    public function testQuizNew()
    {
        $crawler = $this->client->request('GET', '/quiz/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Save')->form();
        
        $values['quiz']['title'] = 'new';
        $values['quiz']['_token'] = $form['quiz[_token]']->getValue();
        $values['quiz']['questions'][0]['text'] = 'text';
        $values['quiz']['questions'][0]['answers'][0]['text'] = 'answer1';
        $values['quiz']['questions'][0]['answers'][0]['correct'] = true;
        $values['quiz']['questions'][0]['answers'][1]['text'] = 'answer2';
        $values['quiz']['questions'][0]['answers'][1]['correct'] = false;
        
        $this->client->request($form->getMethod(), $form->getUri(), $values);
        $quiz = $this->em->getRepository(Quiz::class)->findOneBy(['title' => 'new']);
        
        $this->assertEquals('new', $quiz->getTitle());
    }
    
    public function testQuizEdit()
    {
        $crawler = $this->client->request('GET', '/quiz/9/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Update')->form();
        
        $values['quiz']['title'] = 'edit';
        $values['quiz']['_token'] = $form['quiz[_token]']->getValue();
        $values['quiz']['questions'][0]['text'] = 'text';
        $values['quiz']['questions'][0]['answers'][0]['text'] = 'answer1';
        $values['quiz']['questions'][0]['answers'][0]['correct'] = true;
        $values['quiz']['questions'][0]['answers'][1]['text'] = 'answer2';
        $values['quiz']['questions'][0]['answers'][1]['correct'] = false;
        
        $this->client->request($form->getMethod(), $form->getUri(), $values);
        $quiz = $this->em->getRepository(Quiz::class)->findOneBy(['title' => 'edit']);
        
        $this->assertEquals('edit', $quiz->getTitle());
    }
    
    public function testQuizDelete()
    {
        $quiz = $this->em->getRepository(Quiz::class)->findOneBy(['title' => 'new']);
        
        $crawler = $this->client->request('GET', '/quiz/' . $quiz->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Delete')->form();
        $values['_token'] = $form['_token']->getValue();
        
        $this->client->request('DELETE', '/quiz/' . $quiz->getId(), $values);
        $this->client->followRedirect();
        
        $quiz = $this->em->getRepository(Quiz::class)->find(1);
        $this->assertNull($quiz);
    }
    
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        
        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}