<?php
/**
 * @copyright Copyright (c) 2014 Serhiy Vinichuk
 * @license MIT
 * @author Serhiy Vinichuk <serhiyvinichuk@gmail.com>
 */

namespace nullref\widgets\datatable;

class DataLanguage extends \yii\base\Object
{
    /**
     * @var array Language strings used for WAI-ARIA specific attributes
     */
    public $aria = [
        'sortAscending' => ': activate to sort column ascending',
        'sortDescending' => ': activate to sort column descending',
    ];
    /**
     * @var string Decimal place character
     */
    public $decimal = '.';
    /**
     * @var string Table has no records string
     */
    public $emptyTable = 'No data available in table';
    /**
     * These tokens can be placed anywhere in the string, or removed as needed by the language requires:
     * - _START_ - Display index of the first record on the current page
     * - _END_ - Display index of the last record on the current page
     * - _TOTAL_ - Number of records in the table after filtering
     * - _MAX_ - Number of records in the table without filtering
     * - _PAGE_ - Current page number
     * - _PAGES_ - Total number of pages of data in the table
     * @var string Table summary information display string
     */
    public $info = 'Showing _START_ to _END_ of _TOTAL_ entries';
    /**
     * @var string Table summary information string used when the table is empty or records
     */
    public $infoEmpty = 'Showing 0 to 0 of 0 entries';
    /**
     * @var string Appended string to the summary information when the table is filtered
     */
    public $infoFiltered = '(filtered from _MAX_ total entries)';
    /**
     * @var string String to append to all other summary information strings
     */
    public $infoPostFix = '';
    /**
     * The token _MENU_ is replaced with a value specified by *lengthMenu*
     * @var string Page length options string
     */
    public $lengthMenu = 'Show _MENU_ entries';
    /**
     * @var string Loading information display string - shown when Ajax loading data
     */
    public $loadingRecords = 'Loading...';
    /**
     * @var array Pagination specific language strings
     */
    public $paginate = [
        'first' => 'First',
        'last' => 'Last',
        'next' => 'Next',
        'previous' => 'Previous',
    ];
    /**
     * @var string Processing indicator string
     */
    public $processing = 'Processing...';
    /**
     * @var string Search input string
     */
    public $search = 'Search:';
    /**
     * @var string Search input element placeholder attribute
     */
    public $searchPlaceholder = '';
    /**
     * @var string Thousands separator
     */
    public $thousands = ',';
    /**
     * @var string Load language information from remote file
     */
    public $url;
    /**
     * @var string Table empty as a result of filtering string
     */
    public $zeroRecords = 'No matching records found';
}