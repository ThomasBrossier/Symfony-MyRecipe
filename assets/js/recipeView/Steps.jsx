import React, {useContext, useEffect, useState} from 'react';
import Step from "./components/Step";
import {trim} from "core-js/internals/string-trim";
import {AuthContext} from "../config";



const Base = ({recipeSteps}) => {
    const {recipeUpdate,editMode, setRecipeUpdate, recipe, setRecipe} = useContext(AuthContext);
    const [addingStep, setAddingStep ] = useState(false);
    const [newStep, setNewStep ] = useState("");
    const [error, setError] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');

    const handleNewStepChange = (e)=>{
        setNewStep(e.target.value);
    }
    const addNewStep = ()=>{
        setError(false);
        setErrorMessage("");
        if(trim(newStep) === ""){
            setErrorMessage("Vous devez saisir une étape");
            setError(true)
        }else if (newStep.length < 5){
            setErrorMessage("Une étape ne peut pas faire moins de 5 caractères");
            setError(true)
        }else{
            const newRecipeStep = { content: newStep }
            setRecipe({...recipe, recipeSteps : [...recipe.recipeSteps,newRecipeStep]})
            setRecipeUpdate({...recipeUpdate, addedSteps : [...recipeUpdate.addedSteps, newRecipeStep]})
            setAddingStep(false);
        }
    }

    return (
        <div className="my-1 p-2">
            <div className="d-flex flex-column align-items-start">
                {
                    recipeSteps.map((recipeStep, index)=>{
                        return  <Step key={recipeStep.id+"-"+(Math.random()*10000)} index={index+1} recipeStep={recipeStep}   />
                    })
                }
                {
                    addingStep ?
                    <div className="d-flex flex-row m-2 mx-4 align-items-center  justify-content-evenly w-75">
                        <div className="d-flex flex-column w-75">
                        <textarea className="w-100" value={newStep} onChange={handleNewStepChange} />
                            { error ?  <p className="text-danger mt-2">{errorMessage}</p> : ""}
                        </div>
                        <button className="btn btn-success" onClick={()=>addNewStep()} >Créer</button>
                        <button className="btn btn-secondary" onClick={()=>{setAddingStep(false); setError(false)}} >Annuler</button>
                    </div>
                    :
                    editMode ?
                        <button className="btn btn-secondary" onClick={()=>setAddingStep(true)} >
                            Ajouter une étape<i className="fa-solid fa-plus"></i>
                        </button>

                        : ""
                }


            </div>
        </div>
    );
}
export default Base;