# Tips for working with this document

This document is written using some constructs from [Pandoc](http://pandoc.org/MANUAL.html#pandocs-markdown)-flavored Markdown. The use of an extended Markdown syntax is necessary because neither plain Markdown nor GitHub-flavored Markdown support bibliographic cross-references.  [Pandoc](http://pandoc.org) adds a [simple extension](http://pandoc.org/MANUAL.html#citations) for handling citations and references, which makes it possible to write a technical paper while still using Markdown.

This document uses only a minimum amount of extra Pandoc syntax so that it is remains possible to see _most_ of it rendered on GitHub using GitHub's default Markdown processor. Cross-references will be lost on GitHub, but it works as a way to view the bulk of the contents.  To view the rest, it is necessary to display the HTML produced by Pandoc.

## Rendering and viewing the HTML output

* To render the HTML, a simple method is to use a command line that grabs the raw HTML from GitHub, saves it to a temporary file on the local computer, and calls `open` to open a browser on the file.  The following is a one-line command line to do this, suitable for use on macOS.

```csh
curl -sS -o /tmp/tmp.html 'https://raw.githubusercontent.com/qri-io/papers/master/qri-deterministic_querying/v2.html?token=ABYgI2bsM8OoKZ8i2Clgm8RBQAqUeS5iks5ZjgiHwA%3D%3D' && open /tmp/tmp.html
```
* To simplify previewing the rendered HTML while simultaneously editing the Markdown `.md` source file locally, the `Makefile` provides a target named `autorefresh`.  It relies on an application called [entr](http://entrproject.org).  To use the `autorefresh` Makefile target, after installing `entr` on your computer, simply invoke `make autorefresh` in this directory, and open the document `.html` file in a browser.  Every time you save the `.md` or `.bibtex` file, it should refresh the browser view.


## Software dependencies

* [Pandoc](http://pandoc.org)
* [entr](http://entrproject.org)
* A [LaTeX](https://www.latex-project.org/get/) distribution that includes BibTeX
