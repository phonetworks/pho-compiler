## V1

This folder contains the pho-lib-graphql-parser definition equivalents.

The function of the contents of this folder is to create prototype nodes and edges that can later on be compiled into PHP executables.

The hierarcy is as follows:

1. FileAnalyzer
2. EntityAnalyzer: name, subtype, 
3. then DirectiveAnalyzer: either EdgeDirectiveAnalyzer or NodeDirectiveAnalyzer, but both extend AbstractDirectiveAnalyzer.
5. then ArgumentAnalyzer: like Edge%sArgumentAnalyzer where %s is the directive title. So for edge: it's Labels, Nodes, Properties. For node, it's Edges, Permissions, Properties.
6. What ArgumentAnalyzer does is simple; there is an array variable named $prototype_functions with the argument name its counterpart function

7. Once all that done, it's time for FieldAnalyzer.
8. Field calls Prototype's field->add() method to describe the field and its value with all it can; like whether it's a list, or nullable etc. 

!!! Currently FieldDirective is not analyzed.