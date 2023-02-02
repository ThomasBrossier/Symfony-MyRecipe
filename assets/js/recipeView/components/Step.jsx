import React, {useContext, useEffect, useState} from 'react';
import {AuthContext} from "../../config";
import {trim} from "core-js/internals/string-trim";

const Step = ({recipeStep, index}) => {

    const {editMode, recipe, setRecipe,setSuccess, setSnackBarContent, switchSnackBarOpen} = useContext(AuthContext);
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
    const updateContent = async ()=>{
        if (error){
            return
        }
        const updatedRecipeStep = {...recipeStep};
        updatedRecipeStep.content = stepContent;

        const data = JSON.stringify(updatedRecipeStep);
        let params = {
            body : data,
            method:'POST',
        }
        let response = await fetch('/api/recipe/'+ recipe.id + '/step', params)
        let res = await response.json();
        if(res.status === "200" ){
            const arr = [...recipe.recipeSteps]
            arr.splice(index,1, updatedRecipeStep)
            setRecipe({...recipe, recipeSteps : arr})
        }else{
            setSuccess(false)
        }
        setSnackBarContent(res.result);
        switchSnackBarOpen(true)
        setEditing(false)
    }

    const deleteStep = async ()=>{
        if(confirm("Etes vous sur de vouloir supprimer cette étape ?")){
            const deletedRecipe = {...recipeStep};
            const data = JSON.stringify(deletedRecipe);
            let params = {
                body : data,
                method:'POST',
            }
            let response = await fetch('/api/recipe/'+ recipe.id + '/step/delete', params)
            let res = await response.json();
            if(res.status === "200" ){
                setSuccess(true);
                const arr = [...recipe.recipeSteps]
                arr.splice(index,1);
                setRecipe({...recipe, recipeSteps : arr});
            }else{
                setSuccess(false)
            }
            setSnackBarContent(res.result);
            switchSnackBarOpen(true)
            setEditing(false)

        }
    }

    return (
        <div className="step-row">
            <h4>{index + 1 }</h4>
            {
                !editing ?
                    <>
                        <p className="m-3 w-75">{stepContent}</p>
                        { editMode ?
                            <>
                                <button className="btn btn-primary main-btn" onClick={() => setEditing(true)}><i
                                    className="fa-solid fa-pen-to-square m-0"></i></button>
                                <button className="btn btn-danger mx-1 main-btn" onClick={deleteStep}><i
                                    className="fa-solid fa-trash mx-0"></i></button>
                            </>
                            : ""}
                    </>

                :
                    <>
                        <div className="d-flex flex flex-column flex-grow-1 ">
                            <textarea className="mx-2" value={ stepContent } onChange={handleChange}/>
                            { error ?  <p className="text-danger mt-2">{errorMessage}</p> : ""}
                        </div>
                        <button className="btn btn-success" onClick={()=>updateContent()}>Modifier</button>
                    </>

            }


        </div>
    );
}
export default Step;