##
## @file    Makefile
## @brief   Generate HTML from Markdown with Bibtex references
## @author  Michael Hucka <mhucka@caltech.edu>

# The following are likely to change.

main_file = v2.md
bib_file  = v2.bibtex

# The remainder below is not likely to need changes under most circumstances.

pandoc_args = \
	-f markdown -t html \
	--css=styles/github-markdown-css.css \
	--csl=styles/apa-5th-edition.csl \
	--bibliography $(bib_file) \
	--email-obfuscation=none \
	--number-sections \
	--mathjax \
	--smart

output_file = $(main_file:.md=.html)

default $(output_file): $(main_file) $(bib_file)
	pandoc $(pandoc_args) $(main_file) -o $(output_file)

reload:;
	make
	open $(output_file)

autorefresh:;
	$(ls $(main_file) $(bib_file) | entr 'make reload') &
