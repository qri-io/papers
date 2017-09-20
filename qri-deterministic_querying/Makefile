##
## @file    Makefile
## @brief   Generate HTML from Markdown with Bibtex references
## @author  Michael Hucka <mhucka@caltech.edu>

# The following are likely to change.

main      = deterministic_querying.md
bib       = deterministic_querying.bibtex
template  = templates/GitHub.html5
bib_style = styles/modified-acm-siggraph.csl

# The remainder below is not likely to need changes under most circumstances.

pandoc_doc_args = \
	-f markdown -t html \
	--template=$(template) \
	--csl=$(bib_style) \
	--bibliography $(bib) \
        --metadata link-citations=true \
	--number-sections \
	--email-obfuscation=none \
	--mathjax \
	--standalone \
	--smart

pandoc_toc_args = \
	-t html \
	--template=templates/toc.html5 \
	--toc \
	--number-sections \
	--standalone \
	--smart

output = $(main:.md=.html)

default $(output): $(main) $(bib)
	pandoc $(pandoc_toc_args) $(main) -o toc.tmp
	pandoc $(pandoc_doc_args) $(main) -o $(output).tmp
	cat $(output).tmp | toc=`cat toc.tmp` envsubst '$$toc' > $(output)
	rm -f toc.tmp $(output).tmp

autorefresh:;
	((ls $(main) $(bib) $(template) | entr make reload) &)

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
