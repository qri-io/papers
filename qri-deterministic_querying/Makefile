##
## @file    Makefile
## @brief   Generate HTML from Markdown with Bibtex references
## @author  Michael Hucka <mhucka@caltech.edu>

# The following are likely to change.

main      = v2.md
bib       = v2.bibtex
template  = templates/GitHub.html5
bib_style = styles/modified-acm-siggraph.csl

# The remainder below is not likely to need changes under most circumstances.

pandoc_args = \
	-f markdown -t html \
	--template=$(template) \
	--csl=$(bib_style) \
	--bibliography $(bib) \
        --metadata link-citations=true \
	--email-obfuscation=none \
	--number-sections \
	--mathjax \
	--standalone \
	--smart

output = $(main:.md=.html)

default $(output): $(main) $(bib)
	pandoc $(pandoc_args) $(main) -o $(output)

reload:;
	make
	reload-safari

autorefresh:;
	((ls $(main) $(bib) | entr make reload) &)
