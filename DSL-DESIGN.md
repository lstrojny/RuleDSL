# DSL Design

## Preamble
- What concepts can we borrow that are already known to non-geeks
- Rules should be readable as english sentences and we accept a more cluttered parser (IS vs. WAS vs. WERE)

## Concepts
### Grouping
 - ANY/ALL is used with search UIs and the like


### Boolean logic
 - <PROPOSITION> IS TRUE|FALSE
 - <PROPOSITION> <PREDICATE> <VALUE>


### Predicates
 - IS GREATER THAN
 - IS GREATER THAN OR EQUAL TO
 - IS NOT GREATER THAN
 - IS NOT GREATER THAN OR EQUAL TO
 - IS LESS THAN
 - IS LESS THAN OR EQUAL TO
 - IS NOT LESS THAN
 - IS NOT LESS THAN OR EQUAL TO
 - IS
 - IS NOT

### Access to properties of objects
 - PROPERTY OF SOMETHING

### Date operands
 - TWO MONTHS AGO

### First class types for percentages, dates currencies
 - 10%
 - 1200¤

### Examples
 - LAST LOGIN OF USER WAS LESS THAN TWO MONTHS AGO
 - MEMBERSHIP OF USER IS NOT PREMIUM

### Todos
 - Allow WAS/ARE/WERE as aliases for IS
 - Date handling
 - Percentage handling
 - Currency handling
 - Readable numbers 1 000 000 instead of 1 000 000

## Examples
### Shopping vouchers
```
RETURN 10% IF ALL RULES APPLY
BEGIN
    REVENUE OF CUSTOMER IS GREATER THAN OR EQUAL 100
    GENDER OF CUSTOMER IS FEMALE
END

RETURN 20% IF ALL RULES APPLY
BEGIN
    PURCHASES OF CUSTOMER ARE GREATER THAN OR EQUAL 2
    GROUPS OF CUSTOMER IS FRIENDS
END

RETURN 100¤ IF ANY RULE APPLIES
BEGIN
    PURCHASES OF CUSTOMER ARE GREATER THAN OR EQUAL 10
    REVENUE OF CUSTOMER IS GREATER THAN OR EQUAL 1000
END
```

### Implement tax rules
RETURN 19 IF ALL RULES APPLY
BEGIN
    COUNTRY OF SHIPPING ADDRESS IS GERMANY
    ZIP OF SHIPPING ADDRESS IS NOT 78266
    ZIP OF SHIPPING ADDRESS IS NOT 43423
END
```

### Control content of a start page
```
RETURN FRIEND_TEASER IF ALL RULES APPLY
BEGIN
    FRIENDS OF USER ARE LESS THAN 10
    AGE OF USER IS LESS THAN 30
END
```
