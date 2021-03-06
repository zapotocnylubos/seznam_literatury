<?php

namespace App\Models;

use Nette;

class AuthorManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getAuthors()
    {
        return $this->database->table('authors');
    }

    public function getAuthorValuePairs()
    {
        return $this->getAuthors()->fetchPairs('id', 'full_name');
    }

    public function getAuthor($id)
    {
        return $this->getAuthors()->get($id);
    }

    public function createAuthor($data)
    {
        $this->getAuthors()->insert($data);
    }

    public function updateAuthor($id, $data)
    {
        $this->getAuthor($id)->update($data);
    }

    public function deleteAuthor($id)
    {
        $this->getAuthor($id)->delete();
    }
}