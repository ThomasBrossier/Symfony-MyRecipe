import React, {useContext, useEffect, useState} from 'react';
import {AuthContext} from "../../config";
import {trim} from "core-js/internals/string-trim";

const Step = ({recipeStep, index}) => {

    const {recipeUpdate,editMode, setRecipeUpdate, recipe, setRecipe} = useContext(AuthContext);
    const [editing, setEditing] = useState(false)
    const [error, setError] = useState(false)
    const [errorMessage, setErrorMessage] = useState('');
    const [stepContent , setStepContent ] = useState(recipeStep.content);

    const handleChange = (e)=>{
        const value = e.target.value;
        setStepContent(value);
        setError(false);
        setErrorMessage("");
        if(trim(value) === ""){
            setErrorMessage("Vous devez saisir une étape");
            setError(true)
        }else if (value.length < 5){
            setErrorMessage("Une étape ne peut pas faire moins de 5 caractères");
            setError(true)
        }
    }
    const updateContent = ()=>{
        if (error){
            return
        }
        const updatedRecipeStep = {...recipeStep};
        updatedRecipeStep.content = stepContent;
        const arr = [...recipe.recipeSteps]
        arr.splice(index-1,1, updatedRecipeStep)
        setRecipe({...recipe, recipeSteps : arr})
        setRecipeUpdate({...recipeUpdate, steps : [...recipeUpdate.updatedSteps, updatedRecipeStep]})
        setEditing(false)
    }
    return (
        <div className="step-row d-flex  m-2 mx-4 align-items-center  justify-content-evenly w-75 ">
            <h4>{index}</h4>
            {
                !editing ?
                    <>
                        <p className="m-3 w-75">{stepContent}</p>
                        { editMode ?
                            <>
                                <button className="btn btn-primary main-btn" onClick={() => setEditing(true)}>Editer</button>
                                <button className="btn btn-danger mx-1 main-btn"><i
                                    className="fa-solid fa-trash mx-0"></i></button>
                            </>
                            : ""}
                    </>

                :
                    <>
                        <div className="d-flex flex flex-column w-75">
                            <textarea className="w-100" value={ stepContent } onChange={handleChange}/>
                            { error ?  <p className="text-danger mt-2">{errorMessage}</p> : ""}
                        </div>
                        <button className="btn btn-success" onClick={()=>updateContent()}>Modifier</button>
                    </>

            }


        </div>
    );
}
export default Step;