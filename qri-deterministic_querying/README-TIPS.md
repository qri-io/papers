# Tips for working with this document

* To render the HTML, a simple method is to use a command line that grabs the raw HTML from GitHub, saves it to a temporary file on the local computer, and calls `open` to open a browser on the file.  The following is a one-line command line to do this, suitable for use on macOS.

```csh
curl -sS -o /tmp/tmp.html 'https://raw.githubusercontent.com/qri-io/papers/master/qri-deterministic_querying/v2.html?token=ABYgI2bsM8OoKZ8i2Clgm8RBQAqUeS5iks5ZjgiHwA%3D%3D' && open /tmp/tmp.html
```
* To preview the rendered HTML while editing locally, the `Makefile` has a target named `autorefresh` that requires [entr](http://entrproject.org).  To use it, after installing `entr` on your computer, simply invoke `make autorefresh` in this directory, and open the HTML file in a browser.  Every time you save the `.md` or `.bibtex` file, it should refresh the browser view.
