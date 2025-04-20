<?php

require_once __DIR__.'/../../config/database.php';

class Report
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function createReport($cafeId, $reportType, $currentValue, $proposedValue, $timestamp)
    {
        $query = "INSERT INTO reports (cafe_id, report_type, current_value, proposed_value, timestamp, status) 
                  VALUES (:cafe_id, :report_type, :current_value, :proposed_value, :timestamp, 'pending')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cafe_id', $cafeId, PDO::PARAM_INT);
        $stmt->bindParam(':report_type', $reportType);
        $stmt->bindParam(':current_value', $currentValue);
        $stmt->bindParam(':proposed_value', $proposedValue);
        $stmt->bindParam(':timestamp', $timestamp);

        return $stmt->execute();
    }

    public function getPendingReports()
    {
        $query = "SELECT r.id, r.cafe_id, r.report_type, r.current_value, r.proposed_value, r.timestamp, c.name AS cafe_name 
                  FROM reports r 
                  JOIN cafes c ON r.cafe_id = c.id 
                  WHERE r.status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportById($reportId)
    {
        $query = "SELECT * FROM reports WHERE id = :report_id AND status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':report_id', $reportId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function approveReport($reportId)
    {
        $query = "UPDATE reports SET status = 'approved' WHERE id = :report_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':report_id', $reportId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
