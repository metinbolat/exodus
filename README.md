# Exodus

Simple and flexible data export library for PHP.

```php
$exporter = new ExportManager(new CsvExporter());
$options = new ExportOptions(
    path: '/path/to/export',
    delimiter: ',',
    includeHeaders: true
);
$result = $exporter->process($data, $options);
```

## Installation

You can install the package via composer:

```bash
composer require metin/exodus
```

## Requirements

- PHP 8.2 or higher
- ext-json
- ext-fileinfo
- ext-mbstring

## Basic Usage

### CSV Export

```php
use Metin\Exodus\Core\ExportManager;
use Metin\Exodus\Exporters\CsvExporter;
use Metin\Exodus\Dto\ExportOptions;
// Prepare data
$data = [
    ['name' => 'John Doe', 'email' => 'john@example.com'],
    ['name' => 'Jane Doe', 'email' => 'jane@example.com']
];
// Configure export options
$options = new ExportOptions(
    path: '/path/to/export',
    delimiter: ',',
    includeHeaders: true,
    filename: 'users.csv'
);
// Create exporter and process
$exporter = new ExportManager(new CsvExporter());
$result = $exporter->process($data, $options);
if ($result->success) {
    echo "File exported to: " . $result->path;
} else {
    echo "Export failed: " . $result->error;
}
```

## Features

- Export data to CSV format
- Configurable CSV delimiter
- Optional headers
- Automatic file naming
- Comprehensive error handling
- Data validation
- Type-safe objects

## Testing

The package comes with a PHPUnit test suite. To run the tests:

```bash
composer test
```

### Test Directory Structure

```
tests/
├── TestCase.php              # Base test case with helper methods
├── Dto/                      # Tests for Data Transfer Objects
├── Exporters/               # Tests for Export implementations
└── data/                    # Test data directory (auto-created)
```

### Testing Different Scenarios

The test suite covers:

- CSV export with/without headers
- Custom delimiters
- Auto-generated and custom filenames
- Error scenarios (invalid directory, empty data, etc.)
- Data validation

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
