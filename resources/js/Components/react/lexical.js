import { createEditor } from "lexical";

const config = {
    namespace: 'editor',
    onError: console.error,
    onload: console.info,
    theme: {

    },
};

const editor = createEditor(config);

const contentEditableElement = document.getElementById('editor');
editor.setRootElement(contentEditableElement);

export { editor, contentEditableElement };
