<?php
session_start();
include_once 'Transaction.php';

class SearchHandler
{
    private $transaction;
    private $searchResults;
    private $totalAmount;

    public function __construct()
    {
        $this->transaction = new Transaction();
        $this->searchResults = null;
        $this->totalAmount = 0;
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
            $this->searchTransactions($_POST['date_start'], $_POST['date_end']);
        }
    }

    private function searchTransactions($dateStart, $dateEnd)
    {
        $this->searchResults = $this->transaction->searchTransactions($dateStart, $dateEnd);
        $this->totalAmount = $this->transaction->getTotalAmount();
    }

    public function getSearchResults()
    {
        return $this->searchResults;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }
}

// Instantiate the handler and process the request
$searchHandler = new SearchHandler();
$searchHandler->handleRequest();

// Get search results and total amount for rendering
$result = $searchHandler->getSearchResults();
$output = $searchHandler->getTotalAmount();
?>

<!-- Your HTML to display the search results and total amount -->
