<?php
namespace App\Services;

use PDO;

final class AuditService {
    public function __construct(private PDO $pdo) {}

    public function log(?int $userId, string $action, string $endpoint, string $ip, int $statusCode, ?string $details = null): void {
        $sql = 'INSERT INTO audit_logs (user_id, action, endpoint, ip_address, status_code, details) 
                VALUES (:u, :a, :e, :ip, :s, :d)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':u'  => $userId,
            ':a'  => strtoupper($action),
            ':e'  => $endpoint,
            ':ip' => $ip,
            ':s'  => $statusCode,
            ':d'  => $details
        ]);
    }
}