import React, {useContext, useEffect, useState} from 'react';
import ReactDOM from 'react-dom/client';
import {pathRecipe, pathCategory, pathIngredient, AuthContext} from '../config';
import Base from "./Base";
import Steps from "./Steps";
import {Alert, CircularProgress, Snackbar} from "@mui/material";



const Recipe = ({message, isAuth}) => {

    const [recipe, setRecipe] = useState(JSON.parse(message));
    const [editMode, setEditMode] = useState(isAuth);
    const [isSending, setIsSending] = useState(false);
    const [recipeUpdate, setRecipeUpdate] = useState({
        id: recipe.id,
        steps : [],
        recipeIngredients : [],
    });

    const [snackBarContent , setSnackBarContent] = useState("");
    const [snackBarOpen , switchSnackBarOpen] = useState(false);
    const [success , setSuccess] = useState(true);

    const handleSubmit = async ()=>{
        setIsSending(true);
        const data = JSON.stringify(recipeUpdate);
        let params = {
            body : data,
            method:'POST',
        }
        let response = await fetch('https://127.0.0.1:8000/api/recipe/update/'+ recipe.id, params)
        let res = await response.json();
        if(res.status === "200" ){
            setSuccess(true);
        }else{
            setSuccess(false)
        }
        console.log(res);
        setSnackBarContent(res.result);
        switchSnackBarOpen(true)
        setIsSending(false);
    }

    return (
        <>
        <Snackbar open={snackBarOpen}
                  autoHideDuration={3000}
                  onClose={()=>switchSnackBarOpen(false)}
                  anchorOrigin={{vertical : 'top', horizontal: 'right'} }>
            <Alert severity={ success ? "success" : "error" } sx={{ width: '100%' }}>
                {snackBarContent}
            </Alert>
        </Snackbar>
        <AuthContext.Provider value={{ recipeUpdate,editMode, setRecipeUpdate, recipe, setRecipe }} >
            <img className="border rounded recipe-image" alt="" src={ pathRecipe + recipe.picture }/>
            <div className="my-1 p-2 d-flex flex-column">
                <Base recipeIngredients={recipe.recipeIngredients} />
                <Steps recipeSteps={recipe.recipeSteps} />
                { editMode ?

                    <button className="btn btn-success align-self-end d-flex align-items-center justify-content-between" onClick={()=>handleSubmit()} >
                        { isSending ? <><CircularProgress size={"1rem"} color={'secondary'}/>    Enregistrement... </> :  "Enregistrer les modifications" }
                    </button>

                    :

                    ""}
            </div>
        </AuthContext.Provider>
        </>
    );
}
export default Recipe;
const root = document.getElementById('react-root')
const reactRoot = ReactDOM.createRoot(root);
reactRoot.render(
    <React.StrictMode>
        <Recipe message={root.dataset.message} isAuth={root.dataset.auth}  />
    </React.StrictMode>
);
