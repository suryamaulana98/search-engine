<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class QueryScriptTest extends TestCase
{
    private string $queryScriptPath;
    private string $indexFilePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queryScriptPath = realpath(__DIR__ . '/../../public/query.py');
        $this->indexFilePath = realpath(__DIR__ . '/../../public/book.index');
    }

    /**
     * Test query.py script file exists
     */
    public function test_query_script_exists(): void
    {
        $this->assertFileExists($this->queryScriptPath);
    }

    /**
     * Test book.index file exists
     */
    public function test_book_index_file_exists(): void
    {
        $this->assertFileExists($this->indexFilePath);
    }

    /**
     * Test query.py contains required imports
     */
    public function test_query_script_has_required_imports(): void
    {
        $content = file_get_contents($this->queryScriptPath);
        $this->assertStringContainsString('import json', $content);
        $this->assertStringContainsString('import pickle', $content);
        $this->assertStringContainsString('import sys', $content);
        $this->assertStringContainsString('import re', $content);
    }

    /**
     * Test query.py reads from pickle index
     */
    public function test_query_script_uses_pickle(): void
    {
        $content = file_get_contents($this->queryScriptPath);
        $this->assertStringContainsString('pickle.load', $content);
    }

    /**
     * Test query.py outputs JSON format
     */
    public function test_query_script_outputs_json(): void
    {
        $content = file_get_contents($this->queryScriptPath);
        $this->assertStringContainsString('json.dumps', $content);
    }

    /**
     * Test query.py sorts results by score descending
     */
    public function test_query_script_sorts_by_score(): void
    {
        $content = file_get_contents($this->queryScriptPath);
        $this->assertStringContainsString("key=lambda k: k['score']", $content);
        $this->assertStringContainsString('reverse=True', $content);
    }

    /**
     * Test query.py expects 3 arguments
     */
    public function test_query_script_expects_arguments(): void
    {
        $content = file_get_contents($this->queryScriptPath);
        $this->assertStringContainsString('sys.argv', $content);
        $this->assertStringContainsString('len(sys.argv) != 4', $content);
    }

    /**
     * Test book.index file is not empty
     */
    public function test_book_index_is_not_empty(): void
    {
        $this->assertGreaterThan(0, filesize($this->indexFilePath));
    }
}
