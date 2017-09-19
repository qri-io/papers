<h1 align="justify">Deterministic Querying the Distributed Web</h1>
<h5 align="justify">Brendan O'Brien - sparkle_pony_2000@qri.io</h5>

The term _open data_ espouses to be data behaving like _open source_. _Open source_ has at least one advantage not currently present in the world of open data, a common pool of accumilitive returns.

Open Source software always builds upon other software. Open Data seems to be constantly reinventing the wheel, where individuals consume a dataset to perform some specific operation, where it's assumed that the result of that operation is not of value to anyone else. At any rate, there is no obvious method for contributing the _output_ of an open data process as the _input_ to another open data process.

This proprty is extremely important if Open Data is to live of to it's promise of matching the gains that open source makes. Without accumulating returns, disparate groups of individuals are doomed to repeat each other's work.

The greatest barrier to this property of accumulative returns emerging comes from patterns in modern software stacks, particularly the _positioning of the database_. In many cases, the database is placed at the "heart" of a modern web-application. Data is stored in it's raw, highly structured form within the database with little to no direct connection to the public internet, and then _interpreted outward_ toward the user in different forms.

This makes perfect sense when the primary "view" or representation of this data is in the form of structured HTML:

```
  Database -> HTML Render -> Network -> Web Browser
```

From there it's a natural next step to provide programmatic access to this data via an HTTP API, which can be conceived of as another "view" on the same central database.

```
  Database -> API Encode -> Network -> API Decode
```

This database-in-the-center approach has worked wonderfully to date, but it's worth noting that this approach is the product of a long history of accumulated technical contexts, some of which are now causing a great deal of inefficency. This becomes most clear when looking at the most common pattern:

```
  Database -> API Encode -> Network -> API Decode -> Database
```

This pattern uses at least four servers to take data out of one closed database, across a network and put it into another closed database. All of the above components require some form of software maintainter, every individual request consumes 4 separate processess at a minimum. This pattern heavily favors holding this data as closed information by consequence of the fact that "opening" this data is an active effort that would require additional engineering time, CPU cycles, etc.

Another approach would be to publish data directly onto a network in a discoverable form, and provide tools for querying data on the network directly:

```
  Network -> Decode
```

Renewed attention to distributed, content-addressed networks with a robust linked-data structure provide a firm foundation upon which to build an open data commons that provides greater opportunity for the collective, accumulative advancement of open data.

What follows is a plan for a suite of tools that, when combined provide the necessary components to be able to query a network, with the explicit design goal of faciliting accumulating results, through data that is held on the network.

It is worth noting that the intent of this approach is not necessarily to supplant database technologies as they are traditionally thought of. This form of deterministic querying deliberately accepts a number of major performance tradoffs for the sake of repeatability & interoperability. These queries will be an order of magnitude slower than those in a mature database technology. Losses in performance are intended to made up for by deduplicating computation, and as such this will naturally predispose these techniques to certian use cases over others.

The efficacy of this technique is inversely correlated to how frequently the underlying data changes. Data that is not mutated after initial insertion is ideal for this approach.

### Content Addressing

This technique builds upon data stored on a content-addressed network, the one we have in mind is [IPFS](https://ipfs.io). It's worth reading the IPFS whitepaper in full: [https://github.com/ipfs/papers/raw/master/ipfs-cap2pfs/ipfs-p2p-file-system.pdf](https://github.com/ipfs/papers/raw/master/ipfs-cap2pfs/ipfs-p2p-file-system.pdf).

This decentralized network approach is the foundation of modern, reliable data commons that these techniques aim to support.

One explicit goal of this endeavor is maximizing _hash discovery_. Hash discovery occurs when two disparte parties supply input that resolves to the same hash. Through application of the following techniques, equivalent datasets and semantically-equivallent queries created in disparate places will naturally discover & deduplicate by resolving to the same hash.

In order to maximize hash discovery, the specification must be as precise as possible. Great care must be taken to limit things like optional fields and alternative forms for dataset & query definition.

We compensate for this rigidity by moving nonessential fields into separately-stored metadata, which is then linked & discovered by different methods from the hash discovery approach.


### Resource Definition

This section could have been called "dataset definition", I've elected to use the term "resource" to disambiguate the term dataset which to some means "a collection of tables" and "a single table of data".

We begin with raw data, stored as Comma Separated Values (CSV) format. This CSV data is a _resource_. Namely, structured, deterministic data. 

```
lat,lng,precip,datestamp,title
60.00,-50.049303,349034,2017-02-14,precip measurement one
60.00,-50.049303,430004,2017-02-15,precip measurement two
...
```

Let's assume this data resolves to the following hash on the network:
```
1220cb90f19d806704600e32b152afbad9fdfc91b0216e15585f9fc1044d44c72d5b
```

While this technique should work with any sufficently precise data format, common, widely used formats like CSV, JSON, etc. are heavily preferred as foundational storage formats. In the spirit of Open Data, it's important to adknowledge & support uses outside of these techniques. Storing data in common formats reduces friction, allowing data to flow freely between ecosystems. It is my view that the performance penalties incurred by storing data in common formats are worth paying in favour of interoperability. Some of these performance penalties can be compensated for through sidecar lookup tables, which represent a future area of research.

In order to work with this data in a deterministic way, we require a _resource definition_ to provide precise details about how to interpret the underlying data. Resource definitions form a concrete handle for working with data.

The following definition is heavily inspired by the the Open Knowledge Foundation Data Package format, with the default storage format being Javascript Object Notation (JSON). The actual definition itself attempts to establish parity with [frictionless data specifications](https://specs.frictionlessdata.io) wherever possible. 

> The author finds the frictionless data spec to be an inprecise, underconsidered pile of shit that somehow manages to change too quickly and too slowly at the same time. However, maximizing compatilibily does seem like a good idea, so care is taken to lend specificity to the spec where needed to make datasets machine readable in a definitive manner, and removing support for nonessential fields.

It's important to note that a resource must resolve to one and only one entity, specified by a `path` property in the resource definition. These techniques provide mechanisms for joining & traversing multiple resources.

This example is shown in a human-readable form, for storage on the network the actual output would be in a condensed, non-indented form, with keys sorted by natural (alphabetical) order.

```json
{
  "qri" : "1.0",
  "format" : "text/csv",
  "formatConfig" : {
    "delimiter": ",",
    "doubleQuote": true,
    "lineTerminator": "\r\n",
    "quoteChar": "\"",
    "skipInitialSpace": true,
    "header": true
  },
  "encoding" : "utf-8",
  "compression" : "gzip",
  "length" : 1020356,
  "schema" : {
    "fields" : [
      { "title" : "lat", "type" : "float", "default" : 0 },
      { "title" : "lng", "type" : "float", "default" : 0 },
      { "title" : "precip", "type" : "float", "default" : 0 },
      { "title" : "datestamp", "type" : "datestamp", "default" : 0 },
      { "title" : "title", "type": "string", default: "" }
    ]
  },
  "path" : "1220cb90f19d806704600e32b152afbad9fdfc91b0216e15585f9fc1044d44c72d5b"
}
```

The resource defintion begins with a version string, `qri` for the resource definition specification itself to provide room for changes to the resource defintiion in the future. All shown fields are required.

`format` specifies the format of the raw data MIME type. While any type of data format is theoretically possible, it makes sense to favor standard data formats like csv, JSON, etc. Accellerating querying speed through lookup tables represents an unexplored area of research.

An example `formatConfig` is taken directly from the [Frictionless Data csv dialect specification](https://specs.frictionlessdata.io/csv-dialect/). The purpose of the `formatConfig` field is to remove as much ambiguity as possible about how to interpret the speficied `format`. `text/csv` comes in a number of dialects, and must be cleaned up through configuration to facilitate clear parsing. Certain other formats, like JSON for example, may require no format configuration at all.

`encoding` specifics character encoding, `compression` specifies any compression on the source data, `length` length is the length of the source data in bytes.

`schema` contains the schema definition for the underlying data, pulled directly from the Open Knowledge specification. While a schema is defined here, in the future it may be possible to perform schemaless/NOSQL style queries, which would not require a schema definition. The advantage of a full schema definition in this context is reduced ambiguity.

`path` is the path to the hash of raw data as it resolves on the network. IPFS speficies a POSIX style path specification, which this field should fully support.

It's important to note that this data points to the hash of content on the network, and that this hash is _deterministic_. Queries to this dataset will always run against the same set of bytes. 

For example purposes the hash of the above dataset definition will be:
```
  1220c9bb19b66f7960b6ab7624b9e043969e2ba7181e834441c0303d872832d3fd50
```

The above json dataset definition would be placed on the network. The resulting hash is used for querying.

It's worth noting that the dataset definition most certianly can and will affect the resulting output of a query, as such there must be a distinction between _raw data_ and a _resource_. The term _resource_ refers to this hashed dataset definition, which must contain a reference to raw data that conforms to the definition to be valid.

Noticibly absent from the resource defintion are any "metadata" fields. The deliniating line for inclusion in a resource definition is weather or not the field will affect the interpretation of the dataset. Descriptive, nonessential is instead included in a separate form, which can point to t

### Query Defintion

Following a definition of a dataset form comes a 

```json
{
  "qri" : "1.0",
  "syntax" : "sql",
  "schema" : {
    "fields" : [
      { "title" : "lat", "type" : "float", "default" : 0 },
      { "title" : "lng", "type" : "float", "default" : 0 },
      { "title" : "precip", "type" : "float", "default" : 0 },
      { "title" : "datestamp", "type" : "datestamp", "default" : 0 }
    ]
  },
  "resources" : {
    "a" : 1220c9bb19b66f7960b6ab7624b9e043969e2ba7181e834441c0303d872832d3fd50,
  },
  "query" : "SELECT a.datestamp, a.lat, a.lng, a.precip FROM a WHERE a.precip > 2.0"
}
```

`syntax` provides an identifier string for the query syntax, leaving open support for different syntax engines for querying. This paper presumes an SQL implementation given the widespread popularity of SQL as a reasonable starting point. Other syntaxes would be 

`resources` is a map of all datasets referenced in this query, with alphabetical keys generated by datasets in order of appearance within the query. Keys are _always_ referenced in the form [a-z,aa-zz,aaa-zzz, ...] by order of appearence. The query itself is rewritten to refer to these table names using bind variables

`query` is the is parsed & rewritten to a _standard form_ to maximize hash overlap. Writing a query to it's standard form involves making deterministic choices to remove non-semantic whitespace, rewrite semantically-equivalent terms like "&&" and "AND" to a chosen version, et cetera. Greater precision of querying format will increase the chances of hash discovery.

Assume the hash of the above query is:
```
  1220da93465d97bd4972da16e1c3d9bfb8020c3c31564c9940f2c51bf06f0f523281
```

### Query Execution

Executing the query will be the duty of a query engine, which will accept a query definition as input, and

Querying engines may support only specific

Beyond the general Points of consideration for composing a

### Query Output

The output of running a query is a resource definition & underlying data. A few additional properties are introduced to the resource defintion to specify that it is the result of a query:

```json
{
  "qri" : "1.0",
  "format" : "text/csv",
  "formatConfig" : {
    "delimiter": ",",
    "doubleQuote": true,
    "lineTerminator": "\r\n",
    "quoteChar": "\"",
    "skipInitialSpace": true,
    "header": true
  },
  "encoding" : "utf-8",
  "compression" : "gzip",
  "length" : 1020356,
  "query" : "1220da93465d97bd4972da16e1c3d9bfb8020c3c31564c9940f2c51bf06f0f523281",
  "queryEngine" : "1220c817e686efe3fb8eef21390128fe03c71f45da60a3e71503a54622d52fc2eca8",
  "queryEngineConfig" : {
    "output" : "csv"
  },
  "queryPlatform" : "12200e2712d0b5d93c32229c9f674f17b9f1d2f06ddd1270325730a1352ca3e22637",
  "schema" : {
    "fields" : [
      { "title" : "lat", "type" : "float", "default" : 0 },
      { "title" : "lng", "type" : "float", "default" : 0 },
      { "title" : "precip", "type" : "float", "default" : 0 },
      { "title" : "datestamp", "type" : "datestamp", "default" : 0 },
    ]
  },
  "data" : "122089e59524c473fc61c94024946b3e9154666966987667f249602cd483485e4cbd"
}
```

* `query` is a path to query that output this resource.
* `queryEngine` is the hash of the source code that produced the result.
* `queryPlatform` is the hash of the operating system that performed the query.
* `queryEngineConfig` outlines any configuration that would affect the resulting hash.

Assume the hash of the above output is: 
```
  1220f76a4f68cdb903a20ee3e70d250a77647e2503b1150073cad30eda8381840986
```

### Distrbuted Query Tables

With all of this in place, we now have _determinstic results_. Upon successfully executing a query we write the query and it's resulting data to the network. Additionally, an entry is appended to a _distributed results table_, which connects query hashes to result hashes.

```json
[
  ...
  ["1220da93465d97bd4972da16e1c3d9bfb8020c3c31564c9940f2c51bf06f0f523281": "1220f76a4f68cdb903a20ee3e70d250a77647e2503b1150073cad30eda8381840986"],
  ["1220da93465d97bd4972da16e1c3d9bfb8020c3c31564c9940f2c51bf06f0f523281": "12206e12868621baa1b815c7f6ec72e830c7c1f081ea7d105d9e461cfa951d70ad81"],
  ...
]  
```

A single query can and most likely will have multiple result hashes. Because these result hashes point to resource definitions and not raw data, it is efficent to download & parse the resource definition results, and align results based on the desired outputs. If the user desires query results in CSV format, matches that define results in JSON can be filtered out. If the query in question is deemed to be computationally expensive, the user may elect to tranlate json results to CSV results instead of re-running the calculation.

Before executing a query we calculate the hash of the query, and check the DRT for entries with the key that matches the query hash, if an entry is found, the results may be loaded from the network instead of being recalculated.

This computatinal advantage pales in comparison to the knowledge that this query has in fact been run before. From this point the end user now has a result hash that can act as the input for searching for descriptive metadata and other derived queries that serve as a point of research growth the _exact_ query the user is interested in.

### Query Graph & IPLD

Through inclusion of key metadata structures, the above can be coerced into a graph of datasets:

```json
{
  "query" : "1220c9bb19b66f7960b6ab7624b9e043969e2ba7181e834441c0303d872832d3fd50",
  "dataset" : "1220f76a4f68cdb903a20ee3e70d250a77647e2503b1150073cad30eda8381840986",
  "children" : [
    { "dataset" : "1220c9bb19b66f7960b6ab7624b9e043969e2ba7181e834441c0303d872832d3fd50" }
  ]
}
```

In this form, it is always possible to trace query-derived dataset back to it's source data for as long as the data remains on the network. So long as all necessary query engines, platforms, and hashes are present, it should be possible to traverse machine replicate a query graph completely.

This query graph can then be written to as Inter-Planitary Linked Data, allowing IPFS-native traversal of this query graph, opening up a number of opportunities for interoperability with other tools that may in turn produce resources that contribute back to this query graph by different means.


### Metadata

_Descriptive metadata_ is stored separately from _prescriptive metadata_ to maximize overlap of the formal query & resource definitions. 

This also creates space for subjective claims about datasets, and allows metadata to take on a higher frequency of change in contrast to the underlying definition. In addition, descriptive metadata can and should be _author attributed_, associating descriptive claims about a resource with a cyptographic keypair which may represent a person, group of people, or software.

This metadata format is also subject to massive amounts of change. Design goals should include making this compatible with the DCAT spec, with the one major exception that hashes are acceptable in place of urls.

```json
{
  "qri" : "1.0",
  "meta" : {
    "title" : "Precipitation Observations",
    "description" : "...",
  },
  "subject" : "1220c9bb19b66f7960b6ab7624b9e043969e2ba7181e834441c0303d872832d3fd50"
}
```

It's important to understand that the distinction between descriptive & prescriptive metadata is not one of importance. These resources are useless to humans without descriptive metadata, as they lack even a title for the resource. It is commonly understood that without a proper description of the resource, it is impossible to develop meaninful ontological connections with other resources. At the machine level it may be perfectly fine to compare two fields from different resources named `precip` of type `float`, but it is clearly a concern if one field is measured in millimeteres and the other is measured in inches.

It is absolutely within scope to chain together descriptive metadata about any number of resource definitions to form Directed Acyclic Graphs (DAGs). This encourages resource definitions to participate in multiple DAG histories. This multiple-history approach allows connection between, say, a principle resource & a "cleaned" variant through descriptive metadata. This also allows queries to participate in the history formation process. Using these techniques it is possible to use querying a tool for transforming data into an alternate form, and writing the query to a DAG in a form that facilitates greater machine-repeatbility.


### Future Areas of Research & Additional Writing:
* Query Formats & Languages
* Standard Query Formatting
* Dataset Naming & Namespacing
* Linked Data Structure
* Query Metadata Format
* Streaming, Block Querying