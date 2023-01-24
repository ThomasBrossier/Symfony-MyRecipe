import React, {useContext, useState} from 'react';
import {AuthContext} from "../../config";

const Step = ({recipeStep, index}) => {

    const {recipeUpdate,editMode, setRecipeUpdate, recipe, setRecipe} = useContext(AuthContext);
    const [editing, setEditing] = useState(false)
    const [stepContent , setStepContent ] = useState(recipeStep.content);

    const handleChange = (e)=>{
        setStepContent(e.target.value);
    }
    const updateContent = ()=>{
        const newRecipeStep = {...recipeStep};
        newRecipeStep.content = stepContent;
        const arr = [...recipe.recipeSteps]
        arr.splice(index-1,1, newRecipeStep)
        setRecipe({...recipe, recipeSteps : arr})
        setRecipeUpdate({...recipeUpdate, steps : [...recipeUpdate.steps, newRecipeStep]})
        setEditing(false)
    }
    return (
        <div className="step-row d-flex  m-2 mx-4 align-items-center">
            <h4>{index}</h4>
            {
                editMode && !editing ?
                    <>
                        <p className="m-3">{recipeStep.content}</p>
                        <button className="btn btn-primary" onClick={()=>setEditing(true)}>Editer</button>
                    </>

                :
                    <>
                        <textarea className="w-100" value={stepContent} onChange={(e)=>handleChange(e)} />
                        <button className="btn btn-success" onClick={()=>updateContent()}>Modifier</button>
                    </>

            }


        </div>
    );
}
export default Step;