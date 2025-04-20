
<?php

require_once __DIR__.'/../../config/config.php';
require_once __DIR__.'/../models/Cafe.php';
require_once __DIR__.'/../models/Report.php';

class ReportController
{
    private $reportModel;

    private $cafeModel;

    public function __construct()
    {
        $this->reportModel = new Report;
        $this->cafeModel = new Cafe;
    }

    public function submitReport()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (! $data || ! isset($data['cafeId'], $data['reportType'], $data['currentValue'], $data['proposedValue'], $data['timestamp'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid report data']);

            return;
        }

        $success = $this->reportModel->createReport(
            $data['cafeId'],
            $data['reportType'],
            $data['currentValue'],
            $data['proposedValue'],
            $data['timestamp']
        );

        if ($success) {
            http_response_code(201);
            echo json_encode(['message' => 'Report submitted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to submit report']);
        }
    }

    public function getPendingReports()
    {
        return $this->reportModel->getPendingReports();
    }

    public function confirmReport($reportId)
    {
        $report = $this->reportModel->getReportById($reportId);

        if (! $report) {
            return ['success' => false, 'message' => 'Report not found or already processed'];
        }

        $success = $this->cafeModel->updateCafeFromReport($reportId);

        if ($success) {
            $this->reportModel->approveReport($reportId);

            return ['success' => true, 'message' => 'Report approved and cafe updated!'];
        } else {
            return ['success' => false, 'message' => 'Failed to approve report!'];
        }
    }

    private function deleteReport($reportId)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('DELETE FROM reports WHERE id = :id');
        $stmt->execute([':id' => $reportId]);

        return ['success' => true, 'message' => 'Report deleted successfully.'];
    }

    public function handleReportConfirmation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_report']) && isset($_POST['report_id'])) {
            $reportId = (int) $_POST['report_id'];
            $result = $this->confirmReport($reportId);

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    }

    public function handleReportAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['report_id'])) {
            $action = $_POST['action'];
            $reportId = (int) $_POST['report_id'];

            if ($action === 'confirm') {
                $result = $this->confirmReport($reportId);
            } elseif ($action === 'delete') {
                $result = $this->deleteReport($reportId);
            } else {
                $result = ['success' => false, 'message' => 'Invalid action.'];
            }

            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header('Content-Type: application/json');
    $controller = new ReportController;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['report_id'])) {
        $controller->handleReportAction();
    } else {
        $controller->submitReport();
    }
}
