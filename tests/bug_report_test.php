<?php
require_once __DIR__ . '/../simple_html_dom.php';
use PHPUnit\Framework\TestCase;

/**
 * Tests for bugs reported in the bug tracker
 *
 * @link https://sourceforge.net/p/simplehtmldom/bugs
 * Bug tracker
 */
class bug_report_test extends TestCase {
	private $html;

	protected function setUp() {
		$this->html = new simple_html_dom;
	}

	/**
	 * Bug #97 (plaintext returning conjoined elements)
	 *
	 * Paragraphs ("p" elements) should start on a new line when returned as
	 * plain text.
	 *
	 * **Note**:
	 *
	 * Browsers typically separate multiple paragraphs by blank lines.
	 *
	 * @link https://sourceforge.net/p/simplehtmldom/bugs/97/ Bug #97
	 */
	public function test_bug_97() {
		$doc = <<<HTML
<div><p>PHP Simple HTML DOM Parser</p><p>A PHP based DOM parser</p></div>
HTML;

		$text = $this->html->load($doc)->find('div', 0)->plaintext;

		$this->assertEquals(
			"PHP Simple HTML DOM Parser\n\nA PHP based DOM parser",
			$text
		);
	}

	/**
	 * Bug #116 (problem getting tag attributes)
	 *
	 * Parsing fails on attributes that are not separated by whitespace.
	 *
	 * **Note**:
	 *
	 * The [Markup Validation Service](https://validator.w3.org/#validate_by_input)
	 * reports: No space between attributes.
	 *
	 * @link https://sourceforge.net/p/simplehtmldom/bugs/116/ Bug #116
	 */
	public function test_bug_116() {
		$doc = <<<HTML
<a href="#"title="PHP Simple HTML DOM Parser"></a>
HTML;

		$anchor = $this->html->load($doc)->find('a', 0);

		$this->assertCount(2, $anchor->getAllAttributes());
		$this->assertEquals('#', $anchor->href);
		$this->assertEquals('PHP Simple HTML DOM Parser', $anchor->title);
	}

	/**
	 * Bug #121 (//Comment\n != //Comment\s)
	 *
	 * Replacing newlines results in scripts changing behavior if comments are
	 * placed before functions.
	 *
	 * **Expected Behavior**:
	 *
	 * Script tags should be returned exactly as provided to the parser with all
	 * newlines kept intact.
	 *
	 * **Workaround**:
	 *
	 * Set `$stripRN = false` when loading contents. This will prevent newlines
	 * being replaced by spaces.
	 *
	 * @link https://sourceforge.net/p/simplehtmldom/bugs/121/ Bug #121
	 */
	public function test_bug_121() {
		$doc = <<<HTML
<script>
// alert("PHP Simple HTML DOM Parser");
alert("A PHP based DOM parser");
</script>
HTML;

		$dom = $this->html->load($doc);

		$this->assertEquals($doc, (string)$this->html);
	}

	/**
	 * Bug #127 (Incorrect attribute value gives unexpected results)
	 *
	 * Attributes ending on "\" cause the parser to continue parsing the
	 * remaining document as the attribute value.
	 *
	 * @link https://sourceforge.net/p/simplehtmldom/bugs/127/ Bug #127
	 */
	public function test_bug_127() {
		$doc = <<<HTML
<div id="before"></div>
<a href="#" alt="PHP Simple HTML DOM Parser\">
<div id="after"></div>
HTML;

		$this->html->load($doc);

		$this->assertEquals(
			'PHP Simple HTML DOM Parser\\',
			$this->html->find('a', 0)->alt
		);
	}

	/**
	 * Bug #154 (Fatal error: Call to a member function find() on null)
	 *
	 * The parser incorrectly removes everything between `{` and `}` attempting
	 * to remove "Smarty Scripts" from the DOM. This causes regular text to be
	 * removed as well, if it contains curly braces.
	 *
	 * **Example Code**:
	 *
	 * ```HTML
	 * <div class="before"></div>
	 *   <p>{PHP Simple HTML DOM Parser</p>
	 *   <p>{A PHP based DOM parser}</p>
	 * <div id="after"></div>
	 * ```
	 *
	 * @link https://sourceforge.net/p/simplehtmldom/bugs/154/ Bug #154
	 * @link https://www.smarty.net Smarty
	 */
	public function test_bug_154() {
		$doc = <<<HTML
<p>{PHP Simple HTML DOM Parser</p>
<p>{A PHP based DOM parser}</p>
HTML;

		$this->html->load($doc);

		$this->assertCount(2, $this->html->find('p'));

		$this->assertEquals(
			'{PHP Simple HTML DOM Parser',
			$this->html->find('p', 0)->innertext
		);

		$this->assertEquals(
			'{A PHP based DOM parser}',
			$this->html->find('p', 1)->innertext
		);

		/* With Smarty as text */

		$this->html->load($doc, true, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT, HDOM_SMARTY_AS_TEXT);

		$this->assertCount(1, $this->html->find('p'));

		$this->assertEquals(
			'{PHP Simple HTML DOM Parser</p> <p>{A PHP based DOM parser}',
			$this->html->find('p', 0)->innertext
		);

	}

	/**
	 * Bug #160 (Parsing fails with '<-' + '/' symbols combination in string)
	 *
	 * **Example Code**:
	 *
	 * ```HTML
	 * <div id="before"></div>
	 *   <span>---> PHP Simple HTML DOM Parser <--- A /PHP based DOM parser</span>
	 * <div id="after"></div>
	 * ```
	 *
	 * **Note**:
	 *
	 * The [Markup Validation Service](https://validator.w3.org/#validate_by_input)
	 * reports: Bad character `-` after `<`. Probable cause: Unescaped `<`. Try
	 * escaping it as `&lt;`.
	 *
	 * @link https://sourceforge.net/p/simplehtmldom/bugs/160/ Bug #160
	 * @link https://validator.w3.org/#validate_by_input Markup Validation Service
	 */
	public function test_bug_160() {
		$doc = <<<HTML
<div id="before"></div>
	<span>---> PHP Simple HTML DOM Parser <--- A /PHP based DOM parser</span>
<div id="after"></div>
HTML;

		$this->html->load($doc);

		$this->assertEquals(
			'---> PHP Simple HTML DOM Parser <--- A /PHP based DOM parser',
			$this->html->find('span', 0)->innertext
		);
	}

}