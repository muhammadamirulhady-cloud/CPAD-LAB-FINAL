<?php
namespace App\Repositories;

use PDO;

final class BookRepository {
    public function __construct(private PDO $pdo) {}

    public function all(): array {
        return $this->pdo->query('SELECT * FROM books')->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $b, int $createdBy): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO books (title, author, year, genre, created_by)
             VALUES (:title, :author, :year, :genre, :owner)'
        );
        $stmt->execute([
            ':title'  => trim($b['title']),
            ':author' => trim($b['author']),
            ':year'   => (int)$b['year'],
            ':genre'  => trim($b['genre'] ?? 'Uncategorised'),
            ':owner'  => $createdBy
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $b): void {
        $stmt = $this->pdo->prepare(
            'UPDATE books SET title = :title, author = :author, year = :year, genre = :genre WHERE id = :id'
        );
        $stmt->execute([
            ':id'     => $id,
            ':title'  => trim($b['title']),
            ':author' => trim($b['author']),
            ':year'   => (int)$b['year'],
            ':genre'  => trim($b['genre'] ?? 'Uncategorised')
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}