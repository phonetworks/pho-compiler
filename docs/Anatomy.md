
## Anatomy of a Pho GraphQL Schema File

### Introduction

Pho GraphQL Schema files (shortened as "PGQL files") form the basis of data structures within Pho graphs. You may think of them as database schema files, such as MySQL schema files, that define what the tables and rows would look like. Similarly Pho GraphQL schema files define the constraints that constitute the nodes and edges of a graph.

A PGQL file would look like this:

```
# pho-graphql-v1

type User implements ActorNode 
@edges(in:"User:Follow, Status:Mention", out:"Post, Like, Follow")
@permissions(mod: "0x0e554", mask: "0xeeeee") 
@properties(editable: false, volatile: false, revisionable: false)
@feed(simpleUpdate: "%id% has joined", aggregatedUpdate: "%id% have joined") # onCreate

{
    id: ID,
    birthday: Date,
    about: String,
    password: String
}

type Status implements ObjectNode 
@edges(in:"User:Post, User:Like", out:"Mention")
@permissions(mod: "0x0e444", mask: "0xeeeee") 
@properties(expires: 0, editable: false, volatile: false, revisionable: false)
{
    id: ID,
    status: String @constraints(maxLength: 140)
}

# The Post edge created the Status nodes. So its property is set to be "formative"
type Post implements WriteEdge 
@nodes(head:"Status", tail:"User")
@properties(binding: true, persistent: true, consumer: true, formative: true)
@labels(headSingular:"post", headPlural: "posts", tailSingular: "author", tailPlural: "authors")
{
    id: ID,
    status: String @constraints(maxLength: 140)
}
```

In the example above, we see two nodes (User and Status) and one edge (Post)

Before we dive into the details, we would like to let you know that you may access more public Pho schema recipes from https://github.com/pho-recipes with examplary content from well-known social networks such as Twitter and Facebook.

### 1. Versioning

Pho GraphQL schema definitions may change over time. Therefore we must declare the version in use at the very top with: 


```# pho-graphql-v1```

This allows [the compiler](https://github.com/phonetworks/pho-compiler) to recognize the version and compile interpretable PHP files accordingly.

On this note, it is imperative to mention the build process.

Schema files are written in an open standard. They are designed to be processed by "compilers" into executable or interpretable files in various programming languages. Currently the standard [pho-compiler](https://github.com/phonetworks/pho-compiler) converts the PGQL files into PHP, but more languages may be added/contributed soon. The resulting compiled files set the constraints and rules all nodes and edges would adhere by.

### 2. A word about GraphQL 

GraphQL is originally a query language designed to replace archaic REST. For more information, check out http://graphql.org/

The structure of a Pho GraphQL schema file is as follows:

1. It starts with the **File** at the very core. Each file, in a given directory, are to be compiled by the compiler.
2. Files may include multiple Entities. An **Entity** is either a **Node** or an **Edge**. For more information on graph elements "node" and "edge" check out https://en.wikipedia.org/wiki/Graph_theory
3. An Entity (Node or Edge) may have a number of Field, but at least one (1) **Field** must be present.
4. Both Entities and their Fields may have **Directives** that start with the at sign ("@"). 
5. A Directive may or may not have **Arguments**

To illustrate, in the example below:

```
type SomeNode @properties(binding: true)
{
    id: ID,
    status: String @index
}

type SomeEdge
{
    randomField: ID
}
```

1. The snippet as a whole may be considered a File. 
2. This File has 2 Entities. In this example, one of them is a Node, the other is an Edge (although please note they do **not** conform the Pho GraphQL schema standards)
3. The first entity "SomeNode" has two Fields ("id" and "status"), and the second entity "SomeEdge" has only one ("randomField").
4. The first "SomeNode" comes with a Directive. The second "SomeEdge" does not have any Directives. Additionally, the "status" Field of the "SomeNode" entity has a Directive as well, and it's called "@index".
5. While the first Directive "@properties" comes with an argument, namely "binding" and its boolean value "true", the second Directive "@index" does not come with any arguments. A Directive may have none, single or multiple Arguments.

Last but not least, while in this example both entities are stacked together in a single file, they could have resided in their own separate files as well. 

### 3. Pho Nodes

A Pho node is a GraphQL entity that implements either one of the the following three;

* ActorNode
* ObjectNode
* GraphNode

For more information on node types, check out: http://www.phonetworks.org/reference.html#r2

A Pho node may have one of the following directives;

* Edges
* Permissions
* Properties

#### 3.1 Edges Directive

Defines the incoming and outgoing edges of the node. 

* **in**: Incoming edges are marked with the **in** argument.
* **out**: Outgoing edges are marked with **out** argument.

Example:

```
...
type User implements ActorNode 
@edges(
    in:"User:Follow, Page:Mention, User:Consume, User:Message", 
    out:"Post, Star, Comment, Consume, Message, Create, Follow"
)
...
```

While incoming edges are declared with 

* the pertaining node followed by semicolon, followed by the edge label, 

outgoing edges are declared simply with the edge label.

> https://github.com/phonetworks/pho-compiler/blob/master/src/Pho/Compiler/V1/NodeEdgesArgumentAnalyzer.php


#### 3.2 Permissions Directive

Defines who may access/read/write/subscribe to the node.

This directive accepts two (2) arguments;

1. **mod**: the implicit permissions of the node.
2. **mask**: what the author of the node may or may not change with node's permission levels. 

The permissions are inspired by UNIX' chmod. For more information on Pho ACL (access control lists) model, check out https://github.com/phonetworks/pho-microkernel/blob/master/src/Pho/Kernel/Acl/README.md

Example:

```
...
type User implements ActorNode 
@permissions(mod: "0x07554", mask: "0xfffff")
...
```

> https://github.com/phonetworks/pho-compiler/blob/master/src/Pho/Compiler/V1/NodePermissionsArgumentAnalyzer.php

#### 3.3 Properties Directive

Defines core traits of the node:

* **expires**: whether the nodes expires in a certain period of time. E.g. Snapchat videos would have this field set.
* **volatile**: whether the node is persisted on the disk, or not.
* **editable**: one can edit the contents of the node. E.g. status updates may be **not** "editable".
* **revisionable**: the node will keep its revisions. E.g. wiki pages are "revisionable".

```
...
type User implements ActorNode 
@properties(editable: false, volatile: false, revisionable: false)
...
```


> https://github.com/phonetworks/pho-compiler/blob/master/src/Pho/Compiler/V1/NodePropertiesArgumentAnalyzer.php

#### 3.4 Feed Directive

Defines the feed updates when the node is formed. There may be two arguments:

* **simpleUpdate**: update to publish for flat feeds, or single items.
* **aggregaredUpdate**: update to publish for aggregated feeds with multiple items.

Please note, you may assign variables to updates using ```%var%``` notation where var is equal to a field of the node itself.

    
### 4. Pho Edges

Edges define the relationships between nodes.

#### 4.1 Nodes

Defines the types of head and tail nodes this edge may have.

* **head**: defines head nodes. One may define multiple head nodes, separated by comma (,).
* **tail**: defines tail nodes. One may define multiple tail nodes, separated by comma (,).

```
...
type Message implements MentionEdge 
@nodes(tail:"User", head:"User")
...
```


#### 4.2 Labels

Defines the labels of head and tail nodes. 

* **headSingular**: what the head nodes would be called, in singular form.
* **headPlural**: what the head nodes would be called, in plural form.
* **tailSingular**: what the tail nodes would be called, in singular form.
* **tailPlural**: what the tail nodes would be called, in plural form.
* **feedSimple**: the update for flat feeds or single items. %tail.id% represents the id of the tail node. %head.field% represents the field of the head node. Published when the edge is created and connected.
* **feedAggregated**: the update for aggregated feeds with multiple items. %tail.id% represents the id of the tail node. %head.field% represents the field of the head node. Published when the edge is created and connected.
```
...
type Consume implements ReadEdge 
@labels(headSingular:"read", headPlural: "reads", tailSingular: "reader", tailPlural: "readers")
...
```

The example below illustrates the example above: (where "->" represents edge, and "()" represents node)

* (my reader) -> (my read)
* (my readers) -> (my reads)

In addition to the four labels mentioned above, there are four others that are optional; They are called **callables**
and their existence make the edge itself fetchable via simple getter calls. These are:

* **headCallableSingular**: what the head would call to fetch this node, in singular form.
* **headCallablePlural**: what the head would call to fetch this node, in plural form.
* **tailCallableSingular**: what the tail would call to fetch this node, in singular form.
* **tailCallablePlural**: what the tail would call to fetch this node, in plural form.

To illustrate, if the "headCallablePlural" of an edge is set to be "incomingMessages" then the head node could fetch
this edge simply with the method ```getIncomingMessages()``` or check with ```hasIncomingMessage($id)``` where 
"incomingMessage" is the "headCallableSingular".  

> Regardless the format of the labels (camelized, underscored, upper-camelized etc) the getter and setter methods always
> use the upper-camelized version; e.g. getIncomingMessages for labels like incoming_messages, or incomingMessages or
> IncomingMessages.


#### 4.3 Properties

Defines the edge's core traits:

* **binding**: whether when the edge is deleted, its head node is also deleted.
* **multiplicable**: whether there may be multiple such edges from the tail to the head.
* **persistent**: whether the edge persists on the disk.
* **consumer**: whether the edge, when created (or set), returns itself or the head.
* **notifier**: whether the edge, when connected to a head, notifies the head.
* **subscriber**: whether the edge, when connected to a head, updates head's changes to the tail.
* **formative**: whether the edge, when created, also triggers a head node creation.

```
...
type Consume implements ReadEdge 
@properties(binding: false, persistent: true, consumer: true, formative: false, multiplicable: true)
...
```


### 5. Fields

Fields can be defined as:

1. Whether it's optional, with a question mark (?)
1. Followed by the field Type. Currently available types are; [Date, String, Int, Float ...]
3. Whether it must exist, with an exclamation mark (!)
4. Directives, starting with an at sign (@)

https://github.com/phonetworks/pho-framework/blob/master/src/Pho/Framework/FieldHelper.php is the Pho implementation file that processes these fields.

A field directive may have two types:
1. Mutation directives
2. Constraint directives

#### 5.1 Mutation Directives

These directive may modify the value passed. They are:

* **sha1**: takes the sha1 hash of the given String. Useful for storing passwords.
* **md5**: similar to sha1, but shorter, faster, yet less secure (more prone to collisions)
* **default**: the default value of the Field. Should be given in the format; ```@default(String: "value") by typecasting.
* **now**: sets the current time (in UNIX timestamp) as value.
* **unique**: ensures that given field value exists one and only one time in the data store.
* **index**: indexes the given field, making queries faster on the index.


#### 5.2 Constraint Directives

For String:
	
1. **minLength**: minimum character length.
2. **maxLength**: maximum character length
3. **id**: Pho ID format
4. **regex**: any regular expression without delimiters
5. **format**: the following formats are supported; "ip",  "email", "url", "creditCard", "alpha", "alphaNum"

For Date:

1. **dateAfter**: the date must be after, in US format, e.g. 2/13/2015
2. **dateBefore**: the date must be before, in US format, e.g. 2/13/2015

For Int or Float:

1. **lessThan**
2. **greaterThan**

