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
    const [snackBarContent , setSnackBarContent] = useState("");
    const [snackBarOpen , switchSnackBarOpen] = useState(false);
    const [success , setSuccess] = useState(true);

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
        <AuthContext.Provider value={{ editMode, recipe, setRecipe, setSuccess, setSnackBarContent, switchSnackBarOpen }} >
            <img className="border rounded recipe-image" alt="" src={ pathRecipe + recipe.picture }/>
            <div className="my-1 p-2 d-flex flex-column">
                <Base recipeIngredients={recipe.recipeIngredients} />
                <Steps recipeSteps={recipe.recipeSteps} />
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
