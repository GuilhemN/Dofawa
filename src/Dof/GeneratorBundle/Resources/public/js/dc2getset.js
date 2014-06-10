function generateSetter(data, verb) {
	return  "    /**\n" +
			"     * Set " + data.fieldName + "\n" +
			"     *\n" +
			"     * @param " + data.fieldType + " $" + data.fieldName + "\n" +
			"     * @return " + data.entityName + "\n" +
			"     */\n" +
			"    public function " + (verb ? verb : "set") + data.xetterName + "(" + (data.relation ? (data.fieldType + " ") : "") + "$" + data.fieldName + ((data.relation && data.nullable) ? " = null" : "") + ")\n" +
			"    {\n" +
			"        $this->" + data.fieldName + " = $" + data.fieldName + ";\n" +
			"\n" +
			"        return $this;\n" +
			"    }";
}
function generateAdder(data, verb) {
	return  "    /**\n" +
			"     * Add " + data.fieldName + "\n" +
			"     *\n" +
			"     * @param " + data.fieldType + " $" + data.fieldName + "\n" +
			"     * @return " + data.entityName + "\n" +
			"     */\n" +
			"    public function " + (verb ? verb : "add") + data.xetterSingularName + "(" + (data.relation ? (data.fieldType + " ") : "") + "$" + data.fieldName + ")\n" +
			"    {\n" +
			"        $this->" + data.fieldName + "[] = $" + data.fieldName + ";\n" +
			"\n" +
			"        return $this;\n" +
			"    }";
}
function generateRemover(data, verb) {
	return  "    /**\n" +
			"     * Remove " + data.fieldName + "\n" +
			"     *\n" +
			"     * @param " + data.fieldType + " $" + data.fieldName + "\n" +
			"     * @return " + data.entityName + "\n" +
			"     */\n" +
			"    public function " + (verb ? verb : "remove") + data.xetterSingularName + "(" + (data.relation ? (data.fieldType + " ") : "") + "$" + data.fieldName + ")\n" +
			"    {\n" +
			"        $this->" + data.fieldName + "->removeElement($" + data.fieldName + ");\n" +
			"\n" +
			"        return $this;\n" +
			"    }";
}
function generateGetter(data, verb) {
	return  "    /**\n" +
			"     * Get " + data.fieldName + "\n" +
			"     *\n" +
			"     * @return " + data.fieldType + "\n" +
			"     */\n" +
			"    public function " + (verb ? verb : "get") + data.xetterName + "()\n" +
			"    {\n" +
			"        return $this->" + data.fieldName + ";\n" +
			"    }";
}
function generateCode(data) {
	var xetters = [ ];
	if (data.multiple) {
		xetters.push(generateAdder(data));
		xetters.push(generateRemover(data));
	} else
		xetters.push(generateSetter(data));
	xetters.push(generateGetter(data));
	if (data.fieldType == "boolean")
		xetters.push(generateGetter(data, "is"));
	return "\n" + xetters.join("\n\n");
}

// { fieldName: string, fieldType: string, entityName: string, xetterName: string, xetterSingularName: string, relation: boolean, multiple: boolean, nullable: boolean, generatedCode: string }
formHooks['main-form'] = {
	postExport: function (data) {
		var data2 = Object.create(data);
		if (data2.fieldName && !data2.xetterName) {
			data2.xetterName = data2.fieldName.charAt(0).toUpperCase() + data2.fieldName.substring(1);
			elById("xetter-name").placeholder = data2.xetterName;
		}
		if (data2.xetterName && !data2.xetterSingularName) {
			data2.xetterSingularName = data2.xetterName.replace(/s$/i, "");
			elById("xetter-singular-name").placeholder = data2.xetterSingularName;
		}
		data.generatedCode = generateCode(data2);
	},
	preImport: function (data) {
		if (('relation' in data) && !data.relation) {
			data.multiple = false;
			data.nullable = false;
		} else if (('multiple' in data) && data.multiple)
			data.nullable = false;
	},
	postImport: function (data) {
		elById("nullable").disabled = !elById("relation").checked || elById("multiple").checked;
		elById("multiple").disabled = !elById("relation").checked;
	}
};

$(function () {
	$('input').on('change click', function () {
		syncData('form');
	});
	syncData('form');
});