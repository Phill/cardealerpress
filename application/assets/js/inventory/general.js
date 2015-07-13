//alert(cdp_keywords);
var cdp_typeahead_keywords = cdp_keywords;

var cdp_substringKeywordMatcher = function(strs) {
	return function findMatches(q, cb) {
		var matches, substringRegex;
 
		// an array that will be populated with substring matches
		matches = [];
		// regex used to determine if a string contains the substring `q`
		substrRegex = new RegExp(q, 'i');
		// iterate through the pool of strings and for any string that
		// contains the substring `q`, add it to the `matches` array
		jQuery.each(strs, function(i, str) {
			if (substrRegex.test(str)) {
				matches.push(str);
			}
		});
		cb(matches);
	};
};

jQuery('.search-typeahead .text-search').typeahead({
	hint: true,
	highlight: true,
	minLength: 1
},
{
	name: 'cdp_keywords',
	limit: 10,
	source: cdp_substringKeywordMatcher(cdp_typeahead_keywords)
});