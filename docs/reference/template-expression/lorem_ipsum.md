## lorem_ipsum


**lorem_ipsum(  <br />
&nbsp;&nbsp;bool html = false, <br />
&nbsp;&nbsp;int|array paragraphs = 1,  <br />
&nbsp;&nbsp;int|array sentences = [3, 8],  <br />
&nbsp;&nbsp;int|array words = [3, 10],  <br />
&nbsp;&nbsp;int|array chars = [2,12],  <br />
&nbsp;&nbsp;int punctuationChance = 33  <br />
)**

The `lorem_ipsum` function helps you to generate blind text, that will always change on a reload.

```json
{
    "title": "expr:lorem_ipsum(false, 1, 1, [3, 10], [2, 12], 0)",
    "description": "expr:lorem_ipsum()",
    "content": "expr:lorem_ipsum(true, 10, [7, 10], [5, 20])"
}
```

By default, you don't need any parameter, but with the first you can set if the output is html (`true`) or just plain text (`false`).

The next four parameters control the amount of text, it expects a single int or a tuple array with two int, defining the max and min amount.

**paragraphs:** The number of paragraphs. Each paragraph is separated with break (newline in plaintext and `<p>` in html).

**sentences:** The number of sentences in a paragraph.

**words:** The number of words in a sentence.

**chars:** The number of chars in a word.

The last option is the change of punctuation. That means, how often a comma will appear in a sentence. 
If the punctuation is `0` there will also be no `.` in the end, this is useful for titles without a dot.


