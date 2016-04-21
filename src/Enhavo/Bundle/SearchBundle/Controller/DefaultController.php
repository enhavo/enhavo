<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\SearchBundle\Entity\Index;

/**
 * Matches all 'N' Unicode character classes (numbers)
 */
define('PREG_CLASS_NUMBERS',
    '\x{30}-\x{39}\x{b2}\x{b3}\x{b9}\x{bc}-\x{be}\x{660}-\x{669}\x{6f0}-\x{6f9}' .
    '\x{966}-\x{96f}\x{9e6}-\x{9ef}\x{9f4}-\x{9f9}\x{a66}-\x{a6f}\x{ae6}-\x{aef}' .
    '\x{b66}-\x{b6f}\x{be7}-\x{bf2}\x{c66}-\x{c6f}\x{ce6}-\x{cef}\x{d66}-\x{d6f}' .
    '\x{e50}-\x{e59}\x{ed0}-\x{ed9}\x{f20}-\x{f33}\x{1040}-\x{1049}\x{1369}-' .
    '\x{137c}\x{16ee}-\x{16f0}\x{17e0}-\x{17e9}\x{17f0}-\x{17f9}\x{1810}-\x{1819}' .
    '\x{1946}-\x{194f}\x{2070}\x{2074}-\x{2079}\x{2080}-\x{2089}\x{2153}-\x{2183}' .
    '\x{2460}-\x{249b}\x{24ea}-\x{24ff}\x{2776}-\x{2793}\x{3007}\x{3021}-\x{3029}' .
    '\x{3038}-\x{303a}\x{3192}-\x{3195}\x{3220}-\x{3229}\x{3251}-\x{325f}\x{3280}-' .
    '\x{3289}\x{32b1}-\x{32bf}\x{ff10}-\x{ff19}');

/**
 * Matches all 'P' Unicode character classes (punctuation)
 */
define('PREG_CLASS_PUNCTUATION',
    '\x{21}-\x{23}\x{25}-\x{2a}\x{2c}-\x{2f}\x{3a}\x{3b}\x{3f}\x{40}\x{5b}-\x{5d}' .
    '\x{5f}\x{7b}\x{7d}\x{a1}\x{ab}\x{b7}\x{bb}\x{bf}\x{37e}\x{387}\x{55a}-\x{55f}' .
    '\x{589}\x{58a}\x{5be}\x{5c0}\x{5c3}\x{5f3}\x{5f4}\x{60c}\x{60d}\x{61b}\x{61f}' .
    '\x{66a}-\x{66d}\x{6d4}Unic\x{700}-\x{70d}\x{964}\x{965}\x{970}\x{df4}\x{e4f}' .
    '\x{e5a}\x{e5b}\x{f04}-\x{f12}\x{f3a}-\x{f3d}\x{f85}\x{104a}-\x{104f}\x{10fb}' .
    '\x{1361}-\x{1368}\x{166d}\x{166e}\x{169b}\x{169c}\x{16eb}-\x{16ed}\x{1735}' .
    '\x{1736}\x{17d4}-\x{17d6}\x{17d8}-\x{17da}\x{1800}-\x{180a}\x{1944}\x{1945}' .
    '\x{2010}-\x{2027}\x{2030}-\x{2043}\x{2045}-\x{2051}\x{2053}\x{2054}\x{2057}' .
    '\x{207d}\x{207e}\x{208d}\x{208e}\x{2329}\x{232a}\x{23b4}-\x{23b6}\x{2768}-' .
    '\x{2775}\x{27e6}-\x{27eb}\x{2983}-\x{2998}\x{29d8}-\x{29db}\x{29fc}\x{29fd}' .
    '\x{3001}-\x{3003}\x{3008}-\x{3011}\x{3014}-\x{301f}\x{3030}\x{303d}\x{30a0}' .
    '\x{30fb}\x{fd3e}\x{fd3f}\x{fe30}-\x{fe52}\x{fe54}-\x{fe61}\x{fe63}\x{fe68}' .
    '\x{fe6a}\x{fe6b}\x{ff01}-\x{ff03}\x{ff05}-\x{ff0a}\x{ff0c}-\x{ff0f}\x{ff1a}' .
    '\x{ff1b}\x{ff1f}\x{ff20}\x{ff3b}-\x{ff3d}\x{ff3f}\x{ff5b}\x{ff5d}\x{ff5f}-' .
    '\x{ff65}');

class DefaultController extends Controller
{
    /**
     * Matches Unicode characters that are word boundaries.
     *
     * Characters with the following General_category (gc) property values are used
     * as word boundaries. While this does not fully conform to the Word Boundaries
     * algorithm described in http://unicode.org/reports/tr29, as PCRE does not
     * contain the Word_Break property table, this simpler algorithm has to do.
     * - Cc, Cf, Cn, Co, Cs: Other.
     * - Pc, Pd, Pe, Pf, Pi, Po, Ps: Punctuation.
     * - Sc, Sk, Sm, So: Symbols.
     * - Zl, Zp, Zs: Separators.
     *
     * Non-boundary characters include the following General_category (gc) property
     * values:
     * - Ll, Lm, Lo, Lt, Lu: Letters.
     * - Mc, Me, Mn: Combining Marks.
     * - Nd, Nl, No: Numbers.
     *
     * Note that the PCRE property matcher is not used because we wanted to be
     * compatible with Unicode 5.2.0 regardless of the PCRE version used (and any
     * bugs in PCRE property tables).
     *
     * @see http://unicode.org/glossary
     */
    const PREG_CLASS_WORD_BOUNDARY = <<<'EOD'
\x{0}-\x{2F}\x{3A}-\x{40}\x{5B}-\x{60}\x{7B}-\x{A9}\x{AB}-\x{B1}\x{B4}
\x{B6}-\x{B8}\x{BB}\x{BF}\x{D7}\x{F7}\x{2C2}-\x{2C5}\x{2D2}-\x{2DF}
\x{2E5}-\x{2EB}\x{2ED}\x{2EF}-\x{2FF}\x{375}\x{37E}-\x{385}\x{387}\x{3F6}
\x{482}\x{55A}-\x{55F}\x{589}-\x{58A}\x{5BE}\x{5C0}\x{5C3}\x{5C6}
\x{5F3}-\x{60F}\x{61B}-\x{61F}\x{66A}-\x{66D}\x{6D4}\x{6DD}\x{6E9}
\x{6FD}-\x{6FE}\x{700}-\x{70F}\x{7F6}-\x{7F9}\x{830}-\x{83E}
\x{964}-\x{965}\x{970}\x{9F2}-\x{9F3}\x{9FA}-\x{9FB}\x{AF1}\x{B70}
\x{BF3}-\x{BFA}\x{C7F}\x{CF1}-\x{CF2}\x{D79}\x{DF4}\x{E3F}\x{E4F}
\x{E5A}-\x{E5B}\x{F01}-\x{F17}\x{F1A}-\x{F1F}\x{F34}\x{F36}\x{F38}
\x{F3A}-\x{F3D}\x{F85}\x{FBE}-\x{FC5}\x{FC7}-\x{FD8}\x{104A}-\x{104F}
\x{109E}-\x{109F}\x{10FB}\x{1360}-\x{1368}\x{1390}-\x{1399}\x{1400}
\x{166D}-\x{166E}\x{1680}\x{169B}-\x{169C}\x{16EB}-\x{16ED}
\x{1735}-\x{1736}\x{17B4}-\x{17B5}\x{17D4}-\x{17D6}\x{17D8}-\x{17DB}
\x{1800}-\x{180A}\x{180E}\x{1940}-\x{1945}\x{19DE}-\x{19FF}
\x{1A1E}-\x{1A1F}\x{1AA0}-\x{1AA6}\x{1AA8}-\x{1AAD}\x{1B5A}-\x{1B6A}
\x{1B74}-\x{1B7C}\x{1C3B}-\x{1C3F}\x{1C7E}-\x{1C7F}\x{1CD3}\x{1FBD}
\x{1FBF}-\x{1FC1}\x{1FCD}-\x{1FCF}\x{1FDD}-\x{1FDF}\x{1FED}-\x{1FEF}
\x{1FFD}-\x{206F}\x{207A}-\x{207E}\x{208A}-\x{208E}\x{20A0}-\x{20B8}
\x{2100}-\x{2101}\x{2103}-\x{2106}\x{2108}-\x{2109}\x{2114}
\x{2116}-\x{2118}\x{211E}-\x{2123}\x{2125}\x{2127}\x{2129}\x{212E}
\x{213A}-\x{213B}\x{2140}-\x{2144}\x{214A}-\x{214D}\x{214F}
\x{2190}-\x{244A}\x{249C}-\x{24E9}\x{2500}-\x{2775}\x{2794}-\x{2B59}
\x{2CE5}-\x{2CEA}\x{2CF9}-\x{2CFC}\x{2CFE}-\x{2CFF}\x{2E00}-\x{2E2E}
\x{2E30}-\x{3004}\x{3008}-\x{3020}\x{3030}\x{3036}-\x{3037}
\x{303D}-\x{303F}\x{309B}-\x{309C}\x{30A0}\x{30FB}\x{3190}-\x{3191}
\x{3196}-\x{319F}\x{31C0}-\x{31E3}\x{3200}-\x{321E}\x{322A}-\x{3250}
\x{3260}-\x{327F}\x{328A}-\x{32B0}\x{32C0}-\x{33FF}\x{4DC0}-\x{4DFF}
\x{A490}-\x{A4C6}\x{A4FE}-\x{A4FF}\x{A60D}-\x{A60F}\x{A673}\x{A67E}
\x{A6F2}-\x{A716}\x{A720}-\x{A721}\x{A789}-\x{A78A}\x{A828}-\x{A82B}
\x{A836}-\x{A839}\x{A874}-\x{A877}\x{A8CE}-\x{A8CF}\x{A8F8}-\x{A8FA}
\x{A92E}-\x{A92F}\x{A95F}\x{A9C1}-\x{A9CD}\x{A9DE}-\x{A9DF}
\x{AA5C}-\x{AA5F}\x{AA77}-\x{AA79}\x{AADE}-\x{AADF}\x{ABEB}
\x{E000}-\x{F8FF}\x{FB29}\x{FD3E}-\x{FD3F}\x{FDFC}-\x{FDFD}
\x{FE10}-\x{FE19}\x{FE30}-\x{FE6B}\x{FEFF}-\x{FF0F}\x{FF1A}-\x{FF20}
\x{FF3B}-\x{FF40}\x{FF5B}-\x{FF65}\x{FFE0}-\x{FFFD}
EOD;

    /**
     * Entered search keywords.
     *
     * @var string
     */
    protected $searchExpression;

    /**
     * Array of search words.
     *
     * @var array
     */
    protected $words = array();

    /**
     * Limit of AND and OR.
     *
     * @var integer
     */
    protected $and_or_limit = 8;

    /**
     * Indicates whether the query conditions are simple or complex (LIKE,OR,AND).
     *
     * @var bool
     */
    protected $simple = TRUE;

    /**
     * Parsed-out positive and negative search keys.
     *
     * @var array
     */
    protected $keys = array('positive' => array(), 'negative' => array());

    /**
     * Conditions (AND, OR, ..) which are used in the search expression.
     */
    protected $conditions;

    /**
     * Indicates how many matches for a search query are necessary.
     *
     * @var int
     */
    protected $matches = 0;

    /**
     * Only words with more than 3 letters get indexed.
     *
     * @var int
     */
    protected $minimum_word_size = 3;

    /**
     * Is true if there are to many AND/OR expressions
     *
     * @var bool
     */
    protected $toManyExpressions = false;

    protected $normalizeResults = false;

    //Happens when someone pressed the submit button of the search field.
    public function submitAction(Request $request)
    {
        //at the beginning the normalize is always 0
        $normalize = 0;

        //set the current entry to searchEspression
        $this->searchExpression = $request->get('search');

        // check if there are any keywords entered
        if(!empty($this->searchExpression)) {

            //if there are keywords
            $this->parseSearchExpression();

            //go on if there are not to many AND/OR expressions
            if (!$this->toManyExpressions) {

                //get the search results (language, type, id, calculated_score)
                $results = $this->getDoctrine()
                    ->getRepository('EnhavoSearchBundle:Index')
                    ->getSearchResults($this->conditions, $this->matches, $this->simple);

                //prepare data with found results
                $data = array();
                foreach ($results as $resultIndex) {
                    $currentIndex = $this->getDoctrine()
                        ->getRepository('EnhavoSearchBundle:Index')
                        ->findOneBy(array('id' => $resultIndex['id']));
                    $currentDataset = $currentIndex->getDataset();
                    $dataForSearchResult = array();
                    $dataForSearchResult['type'] = $currentDataset->getType();
                    $dataForSearchResult['bundle'] = $currentDataset->getBundle();
                    $dataForSearchResult['reference'] = $currentDataset->getReference();
                    $data[] = $dataForSearchResult;
                }

                $finalResults = array();
                foreach ($data as $resultData) {

                    //get Element from the entity
                    $currentData = $this->getDoctrine()
                        ->getRepository('Enhavo' . ucfirst($resultData['bundle']) . ':' . ucfirst($resultData['type']))
                        ->findOneBy(array('id' => $resultData['reference']));
                    $finalResults[] = $currentData;
                }

                if ($finalResults) {

                    //return results
                    return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                        'data' => $finalResults
                    ));
                }
            } else {
                //To many AND/OR expressions
                return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                    'data' => 'There are to many AND/OR expressions!'
                ));
            }
        } else {
            //there were no keywords entered
            return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                'data' => 'Please enter some keywords!'
            ));
        }

        return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
            'data' => 'No results'
        ));
    }


    /**
     * Parses the search query into SQL conditions.
     *
     * Sets up the following variables:
     * - $this->keys
     * - $this->words
     * - $this->conditions
     * - $this->simple
     * - $this->matches
     */
    protected function parseSearchExpression() {

        //separates the searchExpression: each word becomes an array -> the first value is the original word with a space added, the second is the optional - sign, and the third is the word without the extra space
        //if it is a quoted string it takes all in between the quotes as "one word"
        preg_match_all('/ (-?)("[^"]+"|[^" ]+)/i', ' ' .  $this->searchExpression , $keywords, PREG_SET_ORDER);

        if (count($keywords) ==  0) {
            return;
        }

        // Classify tokens.
        $in_or = FALSE;
        $limit_combinations = $this->and_or_limit;
        // The first search expression does not count as AND.
        $and_count = -1;
        $or_count = 0;

        //Sort keywords in positive and negative
        foreach ($keywords as $match) {

            //check if there are not to many AND/OR expressions
            if ($or_count && $and_count + $or_count >= $limit_combinations) {
                //To many AND/OR expressions
                $this->toManyExpressions = true;
                break;
            }

            //Checking for quotes
            $phrase = FALSE;
            if ($match[2]{0} == '"') {
                $match[2] = substr($match[2], 1, -1);
                $phrase = TRUE;
                $this->simple = FALSE;
            }

            //Symplify match
            $words = $this->search_simplify($match[2]);
            // Re-explode in case simplification added more words, except when
            // matching a phrase.
            $words = $phrase ? array($words) : preg_split('/ /', $words, -1, PREG_SPLIT_NO_EMPTY);//!!!

            // NOT
            if ($match[1] == '-') {
                $this->keys['negative'] = array_merge($this->keys['negative'], $words);
            }
            // OR
            elseif ($match[2] == 'OR' && count($this->keys['positive'])) {
                $last = array_pop($this->keys['positive']);
                // Starting a new OR?
                if (!is_array($last)) {
                    $last = array($last);
                }
                $this->keys['positive'][] = $last;
                $in_or = TRUE;
                $or_count++;
                continue;
            }
            // AND operator: implied, so just ignore it.
            elseif ($match[2] == 'AND' || $match[2] == 'and') {
                continue;
            }

            // Plain keyword.
            else {//!!!
                if ($in_or) {
                    // Add to last element (which is an array).
                    $this->keys['positive'][count($this->keys['positive']) - 1] = array_merge($this->keys['positive'][count($this->keys['positive']) - 1], $words);
                }
                else {
                    $this->keys['positive'] = array_merge($this->keys['positive'], $words);
                    $and_count++;
                }
            }
            $in_or = FALSE;
        }


        $has_and = FALSE;
        $has_or = FALSE;
        //Prepare AND/OR conditions (Positive matches)
        foreach ($this->keys['positive'] as $key) {

            // Group of ORed terms.
            if (is_array($key) && count($key)) {
                // If we had already found one OR, this is another one AND-ed with the
                // first, meaning it is not a simple query.
                if ($has_or) {
                    $this->simple = FALSE;
                }
                $has_or = TRUE;
                $has_new_scores = FALSE;
                $queryor = array();
                foreach ($key as $or) {
                    list($num_new_scores) = $this->parseWord($or);//!!!
                    $has_new_scores |= $num_new_scores;//!!!
                    $queryor[] = $or;
                }
                if (count($queryor)) {
                    $this->conditions['OR'][] = $queryor;
                    // A group of OR keywords only needs to match once.
                    $this->matches += ($has_new_scores > 0);
                }
            }
            // Single ANDed term.
            else {
                $has_and = TRUE;
                list($num_new_scores, $num_valid_words) = $this->parseWord($key);
                $this->conditions['AND'][] = $key;
                if (!$num_valid_words) {
                    $this->simple = FALSE;
                }
                // Each AND keyword needs to match at least once.
                $this->matches += $num_new_scores;
            }
        }
        if ($has_and && $has_or) {
            $this->simple = FALSE;
        }

        // Negative matches.
        foreach ($this->keys['negative'] as $key) {
            $this->conditions['NOT'][] = $key;
            $this->simple = FALSE;
        }
    }

    /**
     * Parses a word or phrase for parseQuery().
     *
     * Splits a phrase into words. Adds its words to $this->words, if it is not
     * already there. Returns a list containing the number of new words found,
     * and the total number of words in the phrase.
     */
    protected function parseWord($word) {//!!!
        $num_new_scores = 0;
        $num_valid_words = 0;

        // Determine the scorewords of this word/phrase.
        $split = explode(' ', $word);
        foreach ($split as $s) {
            $num = is_numeric($s);
            if ($num || strlen($s) >= $this->minimum_word_size) {
                if (!isset($this->words[$s])) {
                    $this->words[$s] = $s;
                    $num_new_scores++;
                }
                $num_valid_words++;
            }
        }

        // Return matching snippet and number of added words.
        return array($num_new_scores, $num_valid_words);
    }

    /**
     * Simplifies and preprocesses text for searching.
     *
     * Processing steps:
     * - Entities are decoded.
     * - Text is lower-cased and diacritics (accents) are removed.
     * - Punctuation is processed (removed or replaced with spaces, depending on
     *   where it is; see code for details).
     */
    function search_simplify($text) {
        //UTF-8
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        // Lowercase
        $text = strtolower($text);

        // To improve searching for numerical data such as dates, IP addresses
        // or version numbers, we consider a group of numerical characters
        // separated only by punctuation characters to be one piece.
        // This also means that searching for e.g. '20/03/1984' also returns
        // results with '20-03-1984' in them.
        // Readable regexp: ([number]+)[punctuation]+(?=[number])
        $text = preg_replace('/([' . PREG_CLASS_NUMBERS . ']+)[' . PREG_CLASS_PUNCTUATION . ']+(?=[' . PREG_CLASS_NUMBERS . '])/u', '\1', $text);

        // Multiple dot and dash groups are word boundaries and replaced with space.
        // No need to use the unicode modifier here because 0-127 ASCII characters
        // can't match higher UTF-8 characters as the leftmost bit of those are 1.
        $text = preg_replace('/[.-]{2,}/', ' ', $text);

        // The dot, underscore and dash are simply removed. This allows meaningful
        // search behavior with acronyms and URLs. See unicode note directly above.
        $text = preg_replace('/[._-]+/', '', $text);

        // With the exception of the rules above, we consider all punctuation,
        // marks, spacers, etc, to be a word boundary.
        $text = preg_replace('/[' . self::PREG_CLASS_WORD_BOUNDARY . ']+/u', ' ', $text);

        return $text;
    }
}
