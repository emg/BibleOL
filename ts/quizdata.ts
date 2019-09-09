// -*- js -*-
// Copyright © 2018 by Ezer IT Consulting. All rights reserved. E-mail: claus@ezer.dk

// Client-side representation of an exercise


//****************************************************************************************************
// QuizData interface and ExtendedQuizFeatures interface
// 
// Define the data in an exercise.
//
// For a description of these interfaces, see the chapter "The quizdata Variable" in the techical
// documentation.
//
interface QuizData {
    quizid       : number;
    quizFeatures : ExtendedQuizFeatures;
    desc         : string;
    maylocate    : boolean;
    sentbefore   : number;
    sentafter    : number;
    monad2Id     : number[];
    id2FeatVal   : util.str2strArr[]; // The value is normally a string, but in the case of value
                                      // suggestions for a multiple choice question the key ends in
                                      // "!suggest!" and the value is an array of stings containing
                                      // the suggestions
}

interface ExtendedQuizFeatures {
    showFeatures       : string[];
    requestFeatures    : { name : string; usedropdown : boolean; hideFeatures : string[]; }[];
    dontShowFeatures   : string[];
    dontShowObjects    : { content : string; show? : string; } [];
    objectType         : string;
    dontShow           : boolean;
    useVirtualKeyboard : boolean;
}


//****************************************************************************************************
// quizdta variable
//
// Contains the data for an exercise.
// The variable is generated by the server code.
//
declare let quizdata : QuizData;


//****************************************************************************************************
// mayShowFeature function
//
// Determines if the client code is allowed to display a given feature.
//
// When not running an exercise, all features may be displayed, but in an exercise, features that
// the student should provide plus features that are marked as don't show may not be displayed.
//
// Parameters:
//     oType: The type of the current grammar object.
//     origOtype: The original type of the current grammar object. (This can be different from
//                oType when, for example, a feature under "clause" has the name "clause_atom:tab".)
//     feat: The name of the object feature.
//     sigObj: The SentenceGrammarItem containing the feature.
// Returns:
//     True, if the specified feature may be displayed.
//
function mayShowFeature(oType : string, origOtype : string, feat : string, sgiObj : SentenceGrammarItem) : boolean {
    if (!inQuiz)
        return true;

    if (sgiObj.mytype==='GrammarMetaFeature') {
        // GrammarMetaFeatures are comprised of several features. All of them must be displayable
        // for the meta feature to be displayable.

        for (let i in sgiObj.items) {
            if (isNaN(+i)) continue; // Not numeric
            if (!mayShowFeature(oType, origOtype, sgiObj.items[+i].name, sgiObj.items[+i]))
                return false;
        }
        return true;
    }

    let qf : ExtendedQuizFeatures = quizdata.quizFeatures;

    // Emdros object types in dontShowObjects may not be displayed (except for a feature explicitly marked "show")
    for (let ix=0, len=qf.dontShowObjects.length; ix<len; ++ix)
        if (qf.dontShowObjects[ix].content===origOtype) // origOtype is a 'dontShowObject'...
            return qf.dontShowObjects[ix].show===feat; // ...so we only show it if it is in the "show" attribute

    // The object type was not in dontShowObjects. If it is not the sentence unit of the exercise,
    // we may display it.
    if (oType!==qf.objectType)
        return true;

    // For the sentence unit of the quiz, request featues must not be displayed
    for (let ix=0, len=qf.requestFeatures.length; ix<len; ++ix)
        if (qf.requestFeatures[ix].name===feat)
            return false;

    // Don't-show features must not be displayed
    return qf.dontShowFeatures.indexOf(feat)===-1;
}

