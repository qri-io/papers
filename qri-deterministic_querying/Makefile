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

autorefresh:;
	((ls $(main) $(bib) | entr make reload) &)

reload:;
	make
	open -g $(output)

heroku:
	@if [ -n `git ls-files -m` ]; then \
	  echo "* * * * * * * * * * * * * * * * * * * * * * * *"; \
	  echo ""; \
	  echo "Warning: uncommitted git changes remain."; \
	  echo ""; \
	  echo "* * * * * * * * * * * * * * * * * * * * * * * *"; \
	fi
	(cd ..; git subtree push --prefix qri-deterministic_querying heroku master)
