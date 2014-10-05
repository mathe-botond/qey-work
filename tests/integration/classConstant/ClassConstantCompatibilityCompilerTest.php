<?php
namespace apptest;
use qeywork as q;

/**
 * Description of ClassConstantCompatibilityCompilerTest
 *
 * @author Dexx
 */
class ClassConstantCompatibilityCompilerTest 
    extends \PHPUnit_Framework_TestCase{
    
    const RESULT_PHP_FILE = 'ClassConstantTestSubject.php';
    const TEST_FILE = 'ClassConstantTestSubject.prec.php';
    const EXPECTED_RESULT_FILE = 'ClassConstanttestExpectedResult.txt';
    
    private $compiler;
    
    public function setUp() {
        parent::setUp();
        
        $this->compiler = new q\ClassConstantCompatibilityCompiler(__DIR__);
        $this->compiler->addFile(__DIR__ . DIRECTORY_SEPARATOR, self::TEST_FILE);
        $this->compiler->compile();
    }
    
    private function getFileContents() {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . self::RESULT_PHP_FILE);
    }
    
    public function testFileCreated() {
        $this->assertFileExists(__DIR__ . DIRECTORY_SEPARATOR . self::RESULT_PHP_FILE);
    }
    
    public function testNoClassConstantRemained() {
        $content = $this->getFileContents();
        $this->assertNotContains(q\ClassConstantCompatibilityCompiler::CLASS_CONSTANT, $content);
    }
    
    public function testResult() {
        $result = $this->getFileContents();
        $expectedResult = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR
                . self::EXPECTED_RESULT_FILE);
        $this->assertEquals($expectedResult, $result);
    }
    
    public function testNamespace() {
        $content = $this->getFileContents();
        $this->assertContains("'\\\\apptest\\\\AnotherClassConstantTestSubject'", $content);
    }
    
    public function testOtherNamespace() {
        $content = $this->getFileContents();
        $this->assertContains("'\\\\qeywork\\\\Url'", $content);
    }
    
    public function tearDown() {
        parent::tearDown();
        
        unlink(__DIR__ . DIRECTORY_SEPARATOR . self::RESULT_PHP_FILE);
    }
}
