<p align="center">
  <img width="375" height="150" src="https://github.com/phonetworks/commons-php/raw/master/.github/cover-smaller.png">
</p>

# Pho-Compiler <!--[![Build Status](https://travis-ci.org/phonetworks/pho-lib-graph.svg?branch=master)](https://travis-ci.org/phonetworks/pho-lib-graph) [![Code Climate](https://img.shields.io/codeclimate/github/phonetworks/pho-compiler.svg)](https://codeclimate.com/github/phonetworks/pho-compiler)-->

Compiles GraphQL schema into PHP executables for use throughout Phở stack.

## Getting Started

Please note, this repository uses submodules, for unit tests. The recommended way to install pho-compiler is through composer.

```shell
git clone https://github.com/phonetworks/pho-compiler
git submodule init
git submodule update
composer install
```

## Schema

### Recommendations

A Phở-compatible GraphQL schema (also called a **"PhoGQL"** file) is advised to end with the following file extension: **.pgql**

### GraphQL

* PhởGQL is built on top of [GraphQL](http://graphql.org/). For more information on GraphQL specs, please visit  [http://facebook.github.io/graphql/](http://facebook.github.io/graphql/).
* A PhoGQL file may include multiple entity definitions. However, it is advised to use (a) a single definition per file or (b) only a single semantically linked group of node and edge definitions per file.

### Versioning
* PhởGQL files must start with a version declaration. For version 1, the one and only version supported so far, it is ```# pho-graphql-schema-v1```
* This declaration must be at the very top of the file and must not be preceded by any other word or character. 
* The following regular expression pattern is used to match the version: ```/^#( )*pho\-graphql\-v1(\r\n|\r|\n| )+/i```
* All future versions and their patterns will be placed in the file [Types.php](https://github.com/phonetworks/pho-compiler/blob/master/src/Pho/Compiler/Types.php)

### Types
* A PhởGQL definition must implement one of ActorNode, GraphNode, ObjectNode or ReadEdge, SubscribeEdge, TransmitEdge, WriteEdge entities. For more information on these entities, check out [pho-framework](http://github.com/phonetworks/pho-framework).
* While GraphQL allows multiple inheritance, the Phở compiler will evaluate only the first interface declared in each definition.

### Directives
* Directives are case-insensitive. Similarly arguments are case-insensitive too.

## Tips
1. Start by defining the Actor edge
2. Outgoing edges are important.
3. Make sure all outgoing edges are done.
